const { sum } = require('../common.js')
const { calculateNextValue } = require('./common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' });
const lines = input.trim().split('\n').map(line => line.split(' ').map(Number));

console.log(sum(lines.map(sequence => calculateNextValue(sequence))))
