<?php

enum Direction: string {
	case Start = 'S';
	case NS = '|';
	case EW = '-';
	case NE = 'L';
	case NW = 'J';
	case SW = '7';
	case SE = 'F';
	case Ground = '.';
}

class Node {
	public function __construct(public int $x, public int $y, public Direction|null $direction, public int|null $distance = null) {}

	public function visited(): bool {
		return $this->distance !== null;
	}

	public function setDistance(int $distance): Node {
		$this->distance = $distance;
		return $this;
	}
}

class NodeMap {
	protected array $map;

	public Node $start;
	public Node $finish;

	public function __construct(string $source) {
		foreach (explode("\n", trim($source)) as $x => $line) {
			foreach (str_split($line) as $y => $char) {
				if ($char === Direction::Start->value) {
					$this->map[$x][$y] = $this->start = new Node($x, $y, Direction::Start, 0);
				}
				else {
					$this->map[$x][$y] = new Node($x, $y, Direction::from($char));
				}
			}
		}

		$nodes = new SplQueue();
		$nodes->enqueue($this->start);

		while (!$nodes->isEmpty()) {
			$node = $nodes->dequeue();
			$distance = $node->distance + 1;
			foreach ($this->generateConnectedNodes($node) as $child) {
				if (!$child->visited()) {
					$nodes->enqueue($this->finish = $child->setDistance($distance));
				}
			}
		}
	}

	public function generateConnectedNodes(Node $node): Generator {
		$up = $this->map[$node->x - 1][$node->y] ?? null;
		$down = $this->map[$node->x + 1][$node->y] ?? null;
		$left = $this->map[$node->x][$node->y - 1] ?? null;
		$right = $this->map[$node->x][$node->y + 1] ?? null;

		switch ($node->direction) {
			case Direction::Start:
				switch ($up->direction) {
					case Direction::NS:
					case Direction::SE:
					case Direction::SW:
						yield $up;
				}

				switch ($down->direction) {
					case Direction::NS:
					case Direction::NE:
					case Direction::NW:
						yield $down;
				}

				switch ($left->direction) {
					case Direction::EW:
					case Direction::NE:
					case Direction::SE:
						yield $left;
				}

				switch ($right->direction) {
					case Direction::EW:
					case Direction::NW:
					case Direction::SW:
						yield $right;
				}

				break;

			case Direction::NS:
				yield $up;
				yield $down;
				break;

			case Direction::EW:
				yield $left;
				yield $right;
				break;

			case Direction::NE:
				yield $up;
				yield $right;
				break;

			case Direction::NW:
				yield $up;
				yield $left;
				break;

			case Direction::SE:
				yield $down;
				yield $right;
				break;

			case Direction::SW:
				yield $down;
				yield $left;
				break;
		}
	}

	public function getEnclosedNodes(): array {
		$out = [];

		foreach ($this->map as $row) {
			$enclosed = false;
			foreach ($row as $node) {
				if ($node->visited()) {
					switch ($node->direction) {
						case Direction::NS:
						case Direction::SE:
						case Direction::SW:
							$enclosed = !$enclosed;
					}
				}
				elseif ($enclosed) {
					$out[] = $node;
				}
			}
		}

		return $out;
	}
}
