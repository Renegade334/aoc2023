#include "common.h"

int main(int argc, char **argv) {
	hand_t *hands = read_input(1);
	printf("%ld\n", calculate_winnings(hands));
	free(hands);

	return EXIT_SUCCESS;
}
