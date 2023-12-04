const { sum, product } = require('../common.js')
const { generateMatrix, getUnique3x3GridValues } = require('./common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const inputMatrix = input.trim().split('\n').map(line => line.split(''))

const numberMatrix = generateMatrix(inputMatrix.length, inputMatrix[0].length, null)
const numberMap = {}

let numberIndex = 0
for (const [ line, chars ] of inputMatrix.entries()) {
	let digits = [], match = false
	for (const [ pos, char ] of chars.entries()) {
		if (char.match(/\d/)) {
			digits.push(char)
			numberMatrix[line][pos] = numberIndex
			match = true
		}
		else if (match) {
			numberMap[numberIndex++] = Number(digits.join(''))
			digits = []
			match = false
		}
	}
	if (match) numberMap[numberIndex++] = Number(digits.join(''))
}

const result = []
for (const [ line, chars ] of inputMatrix.entries()) {
	for (const [ pos, char ] of chars.entries()) {
		if (char === '*') {
			const indices = getUnique3x3GridValues(numberMatrix, line, pos)
			if (indices.length === 2) result.push(product(indices.map(i => numberMap[i])))
		}
	}
}

console.log(sum(result))
