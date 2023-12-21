function sum(array) {
	return array.reduce((a, b) => a + b)
}

function product(array) {
	return array.reduce((a, b) => a * b)
}

function lcm(array) {
	return array.reduce((a, b) => {
		let x = a
		for (let y = b, z = y; y; y = x % y, x = z, z = y) void 0
		return a * b / x
	})
}

module.exports = { sum, product, lcm }
