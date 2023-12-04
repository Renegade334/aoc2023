function seq(start, length, max) {
	return Array.from({ length }, (e, i) => i + start).filter(n => n <= max)
}

module.exports = { seq }
