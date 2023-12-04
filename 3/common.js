function generateMatrix(rows, columns, value) {
	return Array(rows).fill(null).map(() => Array(columns).fill(value))
}

function fill3x3Grid(matrix, row, column, value) {
	const start = column ? column - 1 : 0, end = column + 2
	for (let i = row - 1; i <= row + 1; i++) matrix[i]?.fill(value, start, end)
}

function getUnique3x3GridValues(matrix, row, column) {
	const values = []
	for (let i = row - 1; i <= row + 1; i++) {
		for (let j = column - 1; j <= column + 1; j++) {
			values.push(matrix[i]?.[j])
		}
	}
	return [ ...new Set(values.filter(value => value != undefined)) ]
}

module.exports = { generateMatrix, fill3x3Grid, getUnique3x3GridValues }
