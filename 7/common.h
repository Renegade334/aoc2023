#ifndef COMMON_H
#define COMMON_H

#define _GNU_SOURCE
#include <stdlib.h>
#include <stdio.h>
#include <string.h>

#define INPUT_SIZE 1000
#define DECK_SIZE 13
#define HAND_SIZE 5

#define INVALID_CARD -1

#define HIGH_CARD 0
#define PAIR 1
#define TWO_PAIR 2
#define THREE_OF_A_KIND 3
#define FULL_HOUSE 4
#define FOUR_OF_A_KIND 5
#define FIVE_OF_A_KIND 6

typedef char card_t;

typedef struct pile {
	card_t card;
	char count;
} pile_t;

typedef struct hand {
	card_t cards[HAND_SIZE];
	pile_t piles[HAND_SIZE];
	int type;
	int winnings;
} hand_t;

long calculate_winnings(const hand_t *hands);

hand_t *read_input(int jokers);

#endif // COMMON_H
