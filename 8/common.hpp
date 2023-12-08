#ifndef COMMON_HPP
#define COMMON_HPP

#include <fstream>
#include <iostream>
#include <map>
#include <memory>
#include <numeric>
#include <regex>
#include <string>
#include <vector>

class Node {
	private:
		const std::string left;
		const std::string right;

	public:
		enum Direction { Left, Right };

		Node(const std::string &left, const std::string &right) : left(left), right(right) {}
		Node(const char *left, const char *right) : left(left), right(right) {}

		const std::string &next(Direction direction) {
			switch (direction) {
				case Left:
					return this->left;
				case Right:
					return this->right;
			}

			throw std::invalid_argument("Direction must be a Node::Direction");
		}
};

class InputData {
	private:
		void load(const std::string &filename) {
			std::ifstream file;
			std::string line;

			file.open(filename);
			if (!file.is_open()) {
				throw std::runtime_error("Unable to read input!");
			}

			static const std::regex directions_regex("[LR]+");
			static const std::regex node_regex("([A-Z]{3}) = \\(([A-Z]{3}), ([A-Z]{3})\\)");
			std::smatch regex_match;

			while (std::getline(file, line)) {
				if (!line.length()) {
					continue;
				}

				if (std::regex_match(line, directions_regex)) {
					for (std::string::const_iterator it = line.cbegin(); it != line.cend(); it++) {
						switch (*it) {
							case 'L':
								this->directions.push_back(Node::Direction::Left);
								break;
							case 'R':
								this->directions.push_back(Node::Direction::Right);
								break;
						}
					}
				}
				else if (std::regex_match(line, regex_match, node_regex)) {
					this->nodes.insert(std::make_pair(
						regex_match[1].str(),
						Node(regex_match[2].str(), regex_match[3].str())
					));
				}
			}

			file.close();
		}

	public:
		typedef std::vector<Node::Direction> DirectionList;
		typedef std::map<std::string, Node> NodeMap;

		DirectionList directions;
		NodeMap nodes;

		InputData(const std::string &filename) {
			this->load(filename);
		}
		InputData(const char *filename) {
			this->load(std::string(filename));
		}
};

#endif // COMMON_HPP
