<?php

enum Cell: string {
	case Forest = '#';
	case Path = '.';
	case SlopeUp = '^';
	case SlopeRight = '>';
	case SlopeDown = 'v';
	case SlopeLeft = '<';
}

class Node {
	public function __construct(public int $x, public int $y, public Cell $cell) {}
}

class PathElement {
	public SplObjectStorage $neighbours;

	public function __construct(public Node $node) {
		$this->neighbours = new SplObjectStorage();
	}

	public function addNeighbours(PathElement ...$elements): PathElement {
		foreach ($elements as $element) {
			$this->neighbours->attach($element, 1);
		}
		return $this;
	}

	public function replaceNeighbour(PathElement $target, PathElement $replacement): PathElement {
		if (!$this->neighbours->contains($target)) {
			return $this;
		}
		$distance = $this->neighbours[$target] + $target->neighbours[$replacement];
		$this->neighbours->detach($target);
		$this->neighbours->attach($replacement, $distance);
		return $this;
	}
}

class Path {
	public function __construct(public PathElement $current, public SplObjectStorage $visited, public $length = 0) {}
}

class NodeMap {
	protected array $map;

	public function __construct(string $source) {
		foreach (explode("\n", trim($source)) as $x => $line) {
			foreach (str_split($line) as $y => $char) {
				$this->map[$x][$y] = new Node($x, $y, Cell::from($char));
			}
		}
	}

	public function makeGraph(bool $slopes): PathElement {
		$graph = [];
		foreach ($this->map as $x => $row) {
			foreach ($row as $y => $node) {
				if ($node->cell !== Cell::Forest) {
					$graph[$x][$y] = new PathElement($node);
				}
			}
		}

		foreach ($graph as $x => $row) {
			foreach ($row as $y => $element) {
				if ($slopes && $element->node->cell !== Cell::Path) {
					switch ($element->node->cell) {
						case Cell::SlopeUp:
							$element->addNeighbours($graph[$x - 1][$y]);
							break;
						case Cell::SlopeRight:
							$element->addNeighbours($graph[$x][$y + 1]);
							break;
						case Cell::SlopeDown:
							$element->addNeighbours($graph[$x + 1][$y]);
							break;
						case Cell::SlopeLeft:
							$element->addNeighbours($graph[$x][$y - 1]);
							break;
					}
				}
				else {
					$element->addNeighbours(...array_filter(
						[
							$graph[$x - 1][$y] ?? null,
							$graph[$x][$y + 1] ?? null,
							$graph[$x + 1][$y] ?? null,
							$graph[$x][$y - 1] ?? null
						],
						fn($element) => $element !== null
					));
				}
			}
		}

		foreach ($graph as $x => $row) {
			foreach ($row as $y => $element) {
				if ($element->neighbours->count() === 2) {
					list($a, $b) = iterator_to_array($element->neighbours, false);
					$a->replaceNeighbour($element, $b);
					$b->replaceNeighbour($element, $a);
				}
			}
		}

		return $graph[0][1];
	}

	public function search(bool $slopes): int {
		$size = count($this->map);
		$target = $this->map[$size - 1][$size - 2];

		$max = 0;

		$stack = new SplStack();
		$stack->push(new Path($this->makeGraph($slopes), new SplObjectStorage()));

		while (!$stack->isEmpty()) {
			$path = $stack->pop();

			if ($path->current->node === $target) {
				$max = max($max, $path->length);
				continue;
			}

			foreach ($path->current->neighbours as $neighbour) {
				if ($path->visited->contains($neighbour)) {
					continue;
				}

				$visited = clone $path->visited;
				$visited->attach($path->current);
				$stack->push(new Path($neighbour, $visited, $path->length + $path->current->neighbours->getInfo()));
			}
		}

		return $max;
	}
}
