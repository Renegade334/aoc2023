const { sum } = require('../common.js')
const { seq } = require('./common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const lines = input.trim().split('\n')

const cardMap = {}

for (const line of lines) {
	let [ , id, winners, numbers ] = line.match(/^Card\s+(\d+):([^|]+)\|(.+)$/)
	winners = winners.trim().split(/\s+/)
	numbers = numbers.trim().split(/\s+/)

	let count = 0
	for (const number of numbers) {
		if (winners.includes(number)) count++
	}

	cardMap[id] = count ? seq(Number(id) + 1, count, lines.length) : []
}

let tickets = [ Object.keys(cardMap) ]
let iteration = 1
do {
	tickets[iteration] = []
	for (const id of tickets[iteration - 1]) {
		tickets[iteration].push(...cardMap[id])
	}
} while (tickets[iteration++].length)

console.log(sum(tickets.map(a => a.length)))
