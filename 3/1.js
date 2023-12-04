const { sum } = require('../common.js')
const { generateMatrix, fill3x3Grid } = require('./common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const inputMatrix = input.trim().split('\n').map(line => line.split(''))

const hitMatrix = generateMatrix(inputMatrix.length, inputMatrix[0].length, false)
for (const [ line, chars ] of inputMatrix.entries()) {
	for (const [ pos, char ] of chars.entries()) {
		if (char.match(/[^.\d]/)) fill3x3Grid(hitMatrix, line, pos, true)
	}
}

const result = []
for (const [ line, chars ] of inputMatrix.entries()) {
	let digits = [], hit = false
	for (const [ pos, char ] of chars.entries()) {
		if (char.match(/\d/)) {
			digits.push(char)
			if (hitMatrix[line][pos]) hit = true
		}
		else if (digits.length) {
			if (hit) result.push(Number(digits.join('')))
			digits = []
			hit = false
		}
	}
	if (hit) result.push(Number(digits.join('')))
}

console.log(sum(result))
