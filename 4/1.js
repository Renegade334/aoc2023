const { sum } = require('../common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const lines = input.trim().split('\n')

const points = lines.map(line => {
	let [ , winners, numbers ] = line.match(/^[^:]+:([^|]+)\|(.+)$/)
	winners = winners.trim().split(/\s+/)
	numbers = numbers.trim().split(/\s+/)

	let count = 0
	for (const number of numbers) {
		if (winners.includes(number)) {
			if (count) count *= 2
			else count = 1
		}
	}

	return count
})

console.log(sum(points))
