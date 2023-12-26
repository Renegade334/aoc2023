from common import make_hailstone
import z3

with open('input', encoding = 'latin1') as input:
	hailstones = [make_hailstone(line.strip()) for line in input]

x, y, z, dx, dy, dz = z3.Ints('x y z dx dy dz')
solver = z3.Solver()

for i, h in enumerate(hailstones):
	t = z3.Int(f't{i}')
	solver.add(
		x + t * dx == h.x + t * h.dx,
		y + t * dy == h.y + t * h.dy,
		z + t * dz == h.z + t * h.dz
	)

solver.check()
model = solver.model()
print(sum([model[v].as_long() for v in [x, y, z]]))
