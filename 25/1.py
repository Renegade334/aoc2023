from math import prod
from networkx import parse_adjlist, stoer_wagner

with open('input', encoding = 'latin1') as input:
	adjlist = [line.strip().replace(': ', ' ') for line in input]

graph = parse_adjlist(adjlist)

cuts, partitions = stoer_wagner(graph)
assert(cuts == 3)

print(prod([len(p) for p in partitions]))
