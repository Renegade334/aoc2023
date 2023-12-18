<?php

enum Direction: int {
	case Left = 0;
	case Right = 1;
	case Up = 2;
	case Down = 3;
}

class Node {
	public function __construct(public int $x, public int $y, public int $cost) {}

	public function getNextCoordinates(Direction $direction): array {
		switch ($direction) {
			case Direction::Left:
				return [ 'x' => $this->x, 'y' => $this->y - 1 ];
			case Direction::Right:
				return [ 'x' => $this->x, 'y' => $this->y + 1 ];
			case Direction::Up:
				return [ 'x' => $this->x - 1, 'y' => $this->y ];
			case Direction::Down:
				return [ 'x' => $this->x + 1, 'y' => $this->y ];
		}
	}
}

class PathElement {
	public int $distance = 0;
	public string $id;

	public function __construct(public Node $node, public ?Direction $direction = null, public int $run = 0, int $distance = 0) {
		$this->id = implode(
			'|',
			[
				spl_object_id($node),
				$this->direction?->value ?? 'N',
				$this->run
			]
		);

		if ($direction !== null) {
			$this->distance = $distance + $node->cost;
		}
	}

	public function isOppositeDirection(Direction $direction): bool {
		return $this->direction !== null ? ($this->direction->value ^ $direction->value) === 1 : false;
	}
}

class NodeMap {
	protected array $map;

	public function __construct(string $source) {
		foreach (explode("\n", trim($source)) as $x => $line) {
			foreach (str_split($line) as $y => $cost) {
				$this->map[$x][$y] = new Node($x, $y, $cost);
			}
		}
	}

	public function traverse(int $minimumRun, int $maximumRun): int {
		$start = $this->map[0][0];
		$target = $this->map[count($this->map) - 1][count($this->map[0]) - 1];

		$queue = new SplPriorityQueue();
		$queue->insert(new PathElement($start), 0);
		$expanded = [];

		while (!$queue->isEmpty()) {
			$element = $queue->extract();
			if ($element->node === $target) {
				return $element->distance;
			}

			if (isset($expanded[$element->id])) {
				continue;
			}

			foreach (Direction::cases() as $direction) {
				if ($element->isOppositeDirection($direction)) {
					continue;
				}

				list('x' => $x, 'y' => $y) = $element->node->getNextCoordinates($direction);
				if (!isset($this->map[$x][$y])) {
					continue;
				}

				if ($element->direction === $direction) {
					if ($element->run >= $maximumRun) {
						continue;
					}
					$run = $element->run + 1;
				}
				else {
					if ($element->direction !== null && $element->run < $minimumRun) {
						continue;
					}
					$run = 1;
				}

				$next = new PathElement(
					$this->map[$x][$y],
					$direction,
					$run,
					$element->distance
				);
				$queue->insert($next, -$next->distance);
			}

			$expanded[$element->id] = true;
		}
	}
}
