#include "common.hpp"

int main(int argc, char **argv) {
	InputData data("input");

	long lcm = 1L;
	for (InputData::NodeMap::const_iterator it = data.nodes.cbegin(); it != data.nodes.cend(); it++) {
		if (it->first.back() != 'A') {
			continue;
		}

		std::string current(it->first);
		long steps = 0L;
		do {
			for (InputData::DirectionList::const_iterator it = data.directions.cbegin(); it != data.directions.cend(); it++) {
				steps++;
				current = data.nodes.at(current).next(*it);
			}
		} while (current.back() != 'Z');
		lcm = std::lcm(lcm, steps);
	}

	std::cout << lcm << std::endl;
	return 0;
}
