#include "common.h"

int main(int argc, char **argv) {
	map_t map;
	read_input(map);

	for (int i = 0; i < STABILITY_ITERCOUNT; i++) {
		cycle(map);
	}

	int base = STABILITY_ITERCOUNT, diff;
	if (!calculate_stable_cycle(map, STABILITY_ITERCOUNT, &base, &diff)) {
		fprintf(stderr, "Unable to find stable cycle after %d iterations.\n", STABILITY_ITERCOUNT);
		return EXIT_FAILURE;
	}

	int iterations = (TOTAL_CYCLES - base - 1) / diff;
	for (int i = base + (iterations * diff); i < TOTAL_CYCLES - 1; i++) {
		cycle(map);
	}

	printf("%d\n", calculate_load(map));

	return EXIT_SUCCESS;
}
