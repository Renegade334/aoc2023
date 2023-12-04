const { sum } = require('../common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const lines = input.trim().split('\n')

const values = lines.map(line => {
	let [ , first, last ] = line.match(/^[^\d]*(\d)(?:.*(\d))?[^\d]*$/)
	return Number(`${first}${last ?? first}`)
})

console.log(sum(values))
