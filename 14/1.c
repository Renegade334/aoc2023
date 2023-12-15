#include "common.h"

int main(int argc, char **argv) {
	map_t map;
	read_input(map);

	tilt(map, DIRECTION_UP);
	printf("%d\n", calculate_load(map));

	return EXIT_SUCCESS;
}
