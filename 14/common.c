#include "common.h"

mapptr_t read_input(mapptr_t dst) {
	FILE *fd = fopen("input", "r");
	if (fd == NULL) {
		fprintf(stderr, "Unable to read input!\n");
		exit(EXIT_FAILURE);
	}

	int line = 0;
	char *buf = NULL;
	size_t len = 0;
	while (line < MAP_SIZE && getline(&buf, &len, fd) != -1) {
		memcpy(dst + line++, buf, LINE_SIZE);
	}

	fclose(fd);

	if (line != MAP_SIZE) {
		fprintf(stderr, "Expected %d lines, but only %d received!\n", MAP_SIZE, line);
		exit(EXIT_FAILURE);
	}

	return dst;
}

mapptr_t tilt(mapptr_t map, int direction) {
	switch (direction) {
		case DIRECTION_UP:
			for (int i = 1; i < MAP_SIZE; i++) {
				for (int j = 0; j < LINE_SIZE; j++) {
					if (map[i][j] != 'O') {
						continue;
					}
					int l = i;
					while (l > 0 && map[l - 1][j] == '.') {
						l--;
					}
					if (i != l) {
						map[i][j] = '.';
						map[l][j] = 'O';
					}
				}
			}
			break;

		case DIRECTION_LEFT:
			for (int j = 1; j < LINE_SIZE; j++) {
				for (int i = 0; i < MAP_SIZE; i++) {
					if (map[i][j] != 'O') {
						continue;
					}
					int l = j;
					while (l > 0 && map[i][l - 1] == '.') {
						l--;
					}
					if (j != l) {
						map[i][j] = '.';
						map[i][l] = 'O';
					}
				}
			}
			break;

		case DIRECTION_DOWN:
			for (int i = MAP_SIZE - 2; i >= 0; i--) {
				for (int j = 0; j < LINE_SIZE; j++) {
					if (map[i][j] != 'O') {
						continue;
					}
					int l = i;
					while (l < MAP_SIZE - 1 && map[l + 1][j] == '.') {
						l++;
					}
					if (i != l) {
						map[i][j] = '.';
						map[l][j] = 'O';
					}
				}
			}
			break;

		case DIRECTION_RIGHT:
			for (int j = LINE_SIZE - 2; j >= 0; j--) {
				for (int i = 0; i < MAP_SIZE; i++) {
					if (map[i][j] != 'O') {
						continue;
					}
					int l = j;
					while (l < LINE_SIZE - 1 && map[i][l + 1] == '.') {
						l++;
					}
					if (j != l) {
						map[i][j] = '.';
						map[i][l] = 'O';
					}
				}
			}
			break;
	}

	return map;
}

mapptr_t cycle(mapptr_t map) {
	tilt(map, DIRECTION_UP);
	tilt(map, DIRECTION_LEFT);
	tilt(map, DIRECTION_DOWN);
	tilt(map, DIRECTION_RIGHT);

	return map;
}

int calculate_load(const mapptr_t map) {
	int sum = 0;

	for (int i = 0; i < MAP_SIZE; i++) {
		for (int j = 0; j < LINE_SIZE; j++) {
			if (map[i][j] == 'O') {
				sum += MAP_SIZE - i;
			}
		}
	}

	return sum;
}

int calculate_stable_cycle(mapptr_t map, int max_iterations, int *base, int *diff) {
	map_t original;
	memcpy(original, map, sizeof(original));

	int b, d;

	for (int i = 0; i < max_iterations; i++) {
		cycle(map);

		if (memcmp(map, original, sizeof(map_t)) == 0) {
			int temp = d;
			d = i - b;
			b = i;
			if (temp == d) {
				*base += b;
				*diff = d;
				return 1;
			}
		}
	}

	return 0;
}
