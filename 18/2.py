from common import Point, move, total

with open('input', encoding = 'latin1') as input:
	lines = [line.strip() for line in input]

position = Point(0, 0)
nodes = [position]
perimeter = 0

for line in lines:
	distance = int(line[-7:-2], 16)
	direction = int(line[-2])

	perimeter += distance
	position = move(position, direction, distance)
	nodes.append(position)

print(total(nodes, perimeter))
