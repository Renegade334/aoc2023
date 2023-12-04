const { sum } = require('../common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const lines = input.trim().split('\n')

const digits = {
	one: 1,
	two: 2,
	three: 3,
	four: 4,
	five: 5,
	six: 6,
	seven: 7,
	eight: 8,
	nine: 9
}

const values = lines.map(line => {
	let first = line.match(/(one|two|three|four|five|six|seven|eight|nine|\d)/)[1]
	first = first in digits ? digits[first] : Number(first)

	let last = line.split('').reverse().join('').match(/(eno|owt|eerht|ruof|evif|xis|neves|thgie|enin|\d)/)[1].split('').reverse().join('')
	last = last in digits ? digits[last] : Number(last)

	return Number(`${first}${last}`)
})

console.log(sum(values))
