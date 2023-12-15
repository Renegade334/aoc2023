#include <stdlib.h>
#include <stdio.h>
#include <string.h>

#define LINE_SIZE 100
#define MAP_SIZE 100
#define TOTAL_CYCLES 1000000000
#define STABILITY_ITERCOUNT 1024

#define DIRECTION_UP 0
#define DIRECTION_LEFT 1
#define DIRECTION_DOWN 2
#define DIRECTION_RIGHT 3

typedef char line_t[LINE_SIZE];
typedef line_t map_t[MAP_SIZE], *mapptr_t;

mapptr_t read_input(mapptr_t dst);
mapptr_t tilt(mapptr_t map, int direction);
mapptr_t cycle(mapptr_t map);
int calculate_load(const mapptr_t map);
int calculate_stable_cycle(mapptr_t map, int max_iterations, int *base, int *diff);
