const { sum } = require('../common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const lines = input.trim().split('\n')

const result = lines.map(line => {
	const [ , id, content ] = line.match(/Game (\d+): (.+)$/)
	const groups = content.split('; ')
	for (const group of groups) {
		const balls = group.split(', ')
		for (const ball of balls) {
			const [ number, colour ] = ball.split(' ')
			switch (colour) {
				case 'red':
					if (number > 12) return null
					break
				case 'green':
					if (number > 13) return null
					break
				case 'blue':
					if (number > 14) return null
					break
				default:
					return null
			}
		}
	}
	return Number(id)
})

console.log(sum(result.filter(Boolean)))
