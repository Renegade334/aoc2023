from numpy import absolute, dot, roll
from typing import NamedTuple

Point = NamedTuple('Point', [('x', int), ('y', int)])

directions: dict[str, int] = {
	'R': 0,
	'D': 1,
	'L': 2,
	'U': 3
}

vectors: list[Point] = [
	Point(0, 1),
	Point(1, 0),
	Point(0, -1),
	Point(-1, 0)
]

def shoelace(nodes: list[Point]) -> int:
	x = [node.x for node in nodes]
	y = [node.y for node in nodes]
	return absolute(dot(x, roll(y, 1)) - dot(y, roll(x, 1))).item() // 2

def pick(area: int, perimeter: int) -> int:
	return area - (perimeter // 2) + 1

def total(nodes: list[Point], perimeter: int) -> int:
	area = shoelace(nodes)
	return pick(area, perimeter) + perimeter

def move(position: Point, direction: int, distance: int) -> Point:
	return Point(
		vectors[direction].x * distance + position.x,
		vectors[direction].y * distance + position.y
	)
