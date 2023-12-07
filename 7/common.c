#include "common.h"

const char deck_standard[DECK_SIZE] = { '2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A' };
const char deck_jokers[DECK_SIZE] = { 'J', '2', '3', '4', '5', '6', '7', '8', '9', 'T', 'Q', 'K', 'A' };

static card_t card_index(char c, const char *deck) {
	char i = DECK_SIZE;
	while (i--) {
		if (c == deck[i]) {
			return i;
		}
	}
	return INVALID_CARD;
}

static int sort_cards(const void *a, const void *b) {
	return *(const card_t *)b - *(const card_t *)a;
}

static int sort_piles(const void *a, const void *b) {
	const pile_t *x = *(const pile_t **)&a, *y = *(const pile_t **)&b;
	return y->count == x->count ? y->card - x->card : y->count - x->count;
}

static int sort_hands(const void *a, const void *b) {
	const hand_t *x = *(const hand_t **)&a, *y = *(const hand_t **)&b;

	if (y->type != x->type) {
		return y->type - x->type;
	}

	for (int i = 0; i < HAND_SIZE; i++) {
		if (y->cards[i] != x->cards[i]) {
			return y->cards[i] - x->cards[i];
		}
	}

	return 0;
}

static void compute_hand(hand_t *hand, int jokers) {
	card_t sorted[HAND_SIZE];
	memcpy(sorted, hand->cards, sizeof(sorted));
	qsort(sorted, HAND_SIZE, sizeof(*sorted), sort_cards);

	pile_t *piles = hand->piles;
	piles[0].card = sorted[0];
	int wildcards = 0;
	for (int c = 0, p = 0; c < HAND_SIZE; c++) {
		if (jokers && sorted[c] == 0) {
			wildcards++;
			continue;
		}
		if (piles[p].card != sorted[c]) {
			piles[++p].card = sorted[c];
		}
		piles[p].count++;
	}
	qsort(piles, HAND_SIZE, sizeof(*piles), sort_piles);
	piles[0].count += wildcards;

	int *type = &hand->type;
	switch (piles[0].count) {
		case 5:
			*type = FIVE_OF_A_KIND;
			break;
		case 4:
			*type = FOUR_OF_A_KIND;
			break;
		case 3:
			*type =
				piles[1].count == 2
				? FULL_HOUSE
				: THREE_OF_A_KIND;
			break;
		case 2:
			*type =
				piles[1].count == 2
				? TWO_PAIR
				: PAIR;
			break;
		case 1:
			*type = HIGH_CARD;
			break;
	}
}

long calculate_winnings(const hand_t *hands) {
	long total = 0L;
	for (int i = 0; i < INPUT_SIZE; i++) {
		total += (INPUT_SIZE - i) * hands[i].winnings;
	}
	return total;
}

hand_t *read_input(int jokers) {
	FILE *fd = fopen("input", "r");
	if (fd == NULL) {
		fprintf(stderr, "Unable to read input!\n");
		exit(EXIT_FAILURE);
	}

	hand_t *hands = calloc(INPUT_SIZE, sizeof(hand_t));

	int line = 0;
	char *buf = NULL;
	size_t len = 0;
	while (line < INPUT_SIZE && getline(&buf, &len, fd) != -1) {
		hand_t *hand = &hands[line];

		for (int p = 0; p < HAND_SIZE; p++) {
			card_t card = card_index(buf[p], jokers ? deck_jokers : deck_standard);
			if (card == INVALID_CARD) {
				fprintf(stderr, "Unable to parse card %c (0x%hd) on input line %d!\n", buf[p], buf[p], line + 1);
				exit(EXIT_FAILURE);
			}
			hand->cards[p] = card;
		}
		hand->winnings = atoi(buf + HAND_SIZE + 1);
		compute_hand(hand, jokers);

		++line;
	}

	fclose(fd);

	if (line != INPUT_SIZE) {
		fprintf(stderr, "Expected %d lines, but only %d received!\n", INPUT_SIZE, line);
		exit(EXIT_FAILURE);
	}

	qsort(hands, INPUT_SIZE, sizeof(*hands), sort_hands);

	return hands;
}
