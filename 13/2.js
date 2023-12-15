const { createMaps, findSymmetry, findFixedReflection, calculateSum } = require('./common.js')

const input = require('node:fs').readFileSync('input', { encoding: 'latin1' })
const maps = createMaps(input)

const total = calculateSum(maps.map(map => findSymmetry(map, findFixedReflection)))
console.log(total)
