<?php

enum Cell: string {
	case Start = 'S';
	case Ground = '.';
	case Rock = '#';
}

class Node {
	function __construct(public int $x, public int $y, public Cell $cell) {}

	function generateNextCoordinates(): Generator {
		yield [ 'x' => $this->x - 1, 'y' => $this->y ];
		yield [ 'x' => $this->x + 1, 'y' => $this->y ];
		yield [ 'x' => $this->x, 'y' => $this->y - 1 ];
		yield [ 'x' => $this->x, 'y' => $this->y + 1 ];
	}
}

class NodeMap {
	protected array $map;

	public function __construct(string $source) {
		foreach (explode("\n", trim($source)) as $x => $line) {
			foreach (str_split($line) as $y => $char) {
				if ($char === Cell::Start->value) {
					$this->start = $this->map[$x][$y] = new Node($x, $y, Cell::Ground);
				}
				else {
					$this->map[$x][$y] = new Node($x, $y, Cell::from($char));
				}
			}
		}
	}

	public function walk(int $distance, bool $wrap): int {
		$size = count($this->map);
		$seen = [];
		$count = [ 0 => 0, 1 => 0 ];
		$sequence = [];

		$nodes = [ $this->start ];

		for ($steps = 1; count($nodes) && $steps <= $distance; $steps++) {
			$parity = $steps % 2;
			$next = [];

			foreach ($nodes as $current) {
				foreach ($current->generateNextCoordinates() as $coordinates) {
					list('x' => $x, 'y' => $y) = $coordinates;
					if (isset($seen["$x|$y"])) {
						continue;
					}

					if ($wrap) {
						$wrapped = [ 'x' => $x % $size, 'y' => $y % $size ];
						if ($wrapped['x'] < 0) {
							$wrapped['x'] += $size;
						}
						if ($wrapped['y'] < 0) {
							$wrapped['y'] += $size;
						}
						$cell = $this->map[$wrapped['x']][$wrapped['y']]->cell;
					}
					else {
						$cell = $this->map[$x][$y]?->cell;
					}

					if ($cell !== Cell::Ground) {
						continue;
					}

					$seen["$x|$y"] = true;
					$count[$parity]++;
					$next[] = new Node($x, $y, $cell);
				}
			}

			$nodes = $next;
			if (!$wrap) {
				continue;
			}

			$remaining = $distance - $steps;
			if ($remaining % $size !== 0) {
				continue;
			}

			array_unshift($sequence, $count[$parity]);
			if (count($sequence) !== 4) {
				continue;
			}

			list($a, $b, $c, $d) = $sequence;
			if ($a - (3 * $b) + (3 * $c) - $d !== 0) {
				array_pop($sequence);
				continue;
			}

			$x = $remaining / $size;
			return (($x ** 2 + $x) * ($a + $c - (2 * $b)) / 2) + ($x * ($a - $b)) + $a;
		}

		return $count[$distance % 2];
	}
}
