#include "common.hpp"

int main(int argc, char **argv) {
	InputData data("input");

	std::string current("AAA");
	int steps = 0;
	do {
		for (InputData::DirectionList::const_iterator it = data.directions.cbegin(); it != data.directions.cend(); it++) {
			steps++;
			current = data.nodes.at(current).next(*it);
		}
	} while (current != "ZZZ");

	std::cout << steps << std::endl;
	return 0;
}
