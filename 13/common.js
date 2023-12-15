function createMaps(input) {
	const maps = []
	let current = []

	for (const line of input.split('\n')) {
		if (!line.length) {
			maps.push(current)
			current = []
		}
		else {
			current.push(line)
		}
	}

	return maps
}

function findSymmetry(map, fn) {
	const x = fn(map)
	if (x !== null) {
		return { line: x, reflected: false }
	}

	const y = fn(reflectMap(map))
	if (y !== null) {
		return { line: y, reflected: true }
	}

	throw new Error('Unable to find symmetry', { cause: map })
}

function findReflection(map) {
	main:
	for (let i = 0; i < map.length - 1; i++) {
		for (let j = 0; j <= i && i + j + 1 < map.length; j++) {
			if (map[i - j] !== map[i + j + 1]) {
				continue main
			}
		}

		return i + 1
	}

	return null
}

function findFixedReflection(map) {
	const length = map[0].length

	main:
	for (let i = 0; i < map.length - 1; i++) {
		let l = null

		for (let j = 0; j <= i && i + j + 1 < map.length; j++) {
			const lines = [ map[i - j], map[i + j + 1] ]

			if (lines[0] === lines[1]) {
				continue
			}
			if (l !== null || !hasSingleDelta(lines[0], lines[1], length)) {
				continue main
			}

			l = i
		}

		if (l !== null) {
			return l + 1
		}
	}

	return null
}

function calculateSum(symmetries) {
	return symmetries.reduce((total, symmetry) => total + ((symmetry.reflected ? 1 : 100) * symmetry.line), 0)
}

function reflectMap(map) {
	const reflected = []

	for (let i = 0; i < map[0].length; i++) {
		reflected.push(map.map(line => line[i]).join(''))
	}

	return reflected
}

function hasSingleDelta(a, b, l) {
	let delta = false

	for (let i = 0; i < l; i++) {
		if (a.charCodeAt(i) !== b.charCodeAt(i)) {
			if (delta) {
				return false
			}

			delta = true
		}
	}

	return delta
}

module.exports = { createMaps, findSymmetry, findReflection, findFixedReflection, calculateSum }
