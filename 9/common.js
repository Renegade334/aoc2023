const { sum } = require('../common.js')

function calculateNextValue(sequence, reverse = false) {
	const jumps = []
	let row = Array.from(sequence)
	while (row.filter(Boolean).length) {
		jumps.push(
			reverse
			? row[1] - row[0]
			: row.at(-1) - row.at(-2)
		)
		row = row.slice(0, -1).map((v, i) => row[i + 1] - v)
	}
	return reverse
		? sequence[0] - jumps.reduceRight((prev, cur) => cur - prev)
		: sequence.at(-1) + sum(jumps)
}

module.exports = { calculateNextValue }
