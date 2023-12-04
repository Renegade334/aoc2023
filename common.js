function sum(array) {
	return array.reduce((a, b) => a + b)
}

function product(array) {
	return array.reduce((a, b) => a * b)
}

module.exports = { sum, product }
