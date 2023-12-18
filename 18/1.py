from common import Point, directions, move, total

with open('input', encoding = 'latin1') as input:
	lines = [line.strip() for line in input]

position = Point(0, 0)
nodes = [position]
perimeter = 0

for line in lines:
	fields = line.split()[:2]
	direction = directions[fields[0]]
	distance = int(fields[1])

	perimeter += distance
	position = move(position, direction, distance)
	nodes.append(position)

print(total(nodes, perimeter))
