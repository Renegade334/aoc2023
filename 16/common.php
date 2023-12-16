<?php

enum Cell: string {
	case Space = '.';
	case MirrorR = '/';
	case MirrorL = '\\';
	case SplitterV = '|';
	case SplitterH = '-';
}

enum Direction: int {
	case Left = 0;
	case Right = 1;
	case Up = 2;
	case Down = 3;
}

class Node {
	protected int $lights = 0;

	public function __construct(public int $x, public int $y, public Cell $type) {}

	public function light(Direction $direction): Node {
		$this->lights |= (1 << $direction->value);
		return $this;
	}

	public function unlight(): Node {
		$this->lights = 0;
		return $this;
	}

	public function isLit(): bool {
		return (bool) $this->lights;
	}

	public function isLitInDirection(Direction $direction): bool {
		return (bool) ($this->lights & (1 << $direction->value));
	}

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

class Walker {
	public function __construct(public Node $node, public Direction $direction) {}

	public function generateNextDirections(): Generator {
		switch ($this->node->type) {
			case Cell::Space:
				yield $this->direction;
				break;
			case Cell::MirrorR:
				yield Direction::from($this->direction->value ^ 3);
				break;
			case Cell::MirrorL:
				yield Direction::from(($this->direction->value + 2) & 3);
				break;
			case Cell::SplitterV:
				switch ($this->direction) {
					case Direction::Left:
					case Direction::Right:
						yield Direction::Up;
						yield Direction::Down;
						break;
					default:
						yield $this->direction;
				}
				break;
			case Cell::SplitterH:
				switch ($this->direction) {
					case Direction::Up:
					case Direction::Down:
						yield Direction::Left;
						yield Direction::Right;
						break;
					default:
						yield $this->direction;
				}
				break;
		}
	}
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

	public function walk(int $x, int $y, Direction $direction): int {
		$walkers = new SplStack();
		$walkers->push(new Walker($this->map[$x][$y], $direction));

		$visited = new SplObjectStorage();

		while (!$walkers->isEmpty()) {
			$walker = $walkers->pop();
			$visited->attach($walker->node);
			foreach ($walker->generateNextDirections() as $direction) {
				if ($walker->node->isLitInDirection($direction)) {
					continue;
				}
				$walker->node->light($direction);
				list('x' => $x, 'y' => $y) = $walker->node->getNextCoordinates($direction);
				if (!isset($this->map[$x][$y])) {
					continue;
				}
				$walkers->push(new Walker($this->map[$x][$y], $direction));
			}
		}

		return $visited->count();
	}

	public function reset(): NodeMap {
		foreach ($this->map as $row) {
			foreach ($row as $node) {
				$node->unlight();
			}
		}

		return $this;
	}

	public function getLongestPath(): int {
		$max = 0;

		$length = count($this->map);
		for ($i = 0; $i < $length; $i++) {
			$max = max($max, $this->walk($i, 0, Direction::Right));
			$this->reset();
		}

		$length = count($this->map[0]);
		for ($i = 0; $i < $length; $i++) {
			$max = max($max, $this->walk(0, $i, Direction::Down));
			$this->reset();
		}

		return $max;
	}
}
