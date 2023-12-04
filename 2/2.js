const { sum } = require('../common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const lines = input.trim().split('\n')

const result = lines.map(line => {
	const [ , id, content ] = line.match(/Game (\d+): (.+)$/)
	const groups = content.split('; ')
	let red = 0, blue = 0, green = 0

	for (const group of groups) {
		const balls = group.split(', ')
		for (const ball of balls) {
			const [ number, colour ] = ball.split(' ')
			switch (colour) {
				case 'red':
					red = Math.max(red, number)
					break
				case 'green':
					green = Math.max(green, number)
					break
				case 'blue':
					blue = Math.max(blue, number)
					break
				default:
					return null
			}
		}
	}

	return red * green * blue
})

console.log(sum(result.filter(Boolean)))
