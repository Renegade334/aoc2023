from common import intersect, make_hailstone
from itertools import combinations

with open('input', encoding = 'latin1') as input:
	hailstones = [make_hailstone(line.strip()) for line in input]

count = 0
for c in combinations(hailstones, 2):
	try:
		p = intersect(*c)
	except ArithmeticError:
		continue

	for d in p:
		if (200_000_000_000_000 <= d <= 400_000_000_000_000):
			pass
		else:
			break
	else:
		count += 1

print(count)
