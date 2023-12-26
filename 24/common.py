from math import copysign
from re import match as regexmatch
from typing import NamedTuple

Hailstone = NamedTuple('Hailstone', [('x', int), ('y', int), ('z', int), ('dx', int), ('dy', int), ('dz', int)])
Point = NamedTuple('Point', [('x', int), ('y', int)])

def make_hailstone(line: str) -> Hailstone:
	return Hailstone(*[int(n) for n in regexmatch(r'(-?\d+), (-?\d+), (-?\d+) @ (-?\d+), (-?\d+), (-?\d+)', line).groups()])

def intersect(a: Hailstone, b: Hailstone) -> Point:
	det = a.dy * b.dx - a.dx * b.dy
	if not det:
		raise ArithmeticError('Trajectories are parallel')

	a0 = a.x * a.dy - a.y * a.dx
	b0 = b.x * b.dy - b.y * b.dx
	p = Point((b.dx * a0 - a.dx * b0) / det, (b.dy * a0 - a.dy * b0) / det)

	for origin in [a, b]:
		for axis in p._fields:
			if copysign(1, getattr(p, axis) - getattr(origin, axis)) != copysign(1, getattr(origin, f'd{axis}')):
				raise ArithmeticError('Hailstones intersect in the past')

	return p
