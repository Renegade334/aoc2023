<?php

function popcount(int $x): int {
	$count = 0;
	while ($x) {
		$x &= $x - 1;
		$count++;
	}
	return $count;
}

function generate_solutions(string $pattern, array $counts, array &$cache): int {
	$length = strlen($pattern);
	if ($length === 0) {
		return count($counts) ? 0 : 1;
	}

	if (count($counts) === 0) {
		return strpos($pattern, '#') !== false ? 0 : 1;
	}

	if ($pattern[0] === '.') {
		return solutions(ltrim($pattern, '.'), $counts, $cache);
	}

	if (array_sum($counts) + count($counts) - 1 > $length) {
		return 0;
	}

	if ($pattern[0] === '?') {
		return solutions('.' . substr($pattern, 1), $counts, $cache)
			+ solutions('#' . substr($pattern, 1), $counts, $cache);
	}

	$target = $counts[0];
	if (strpos(substr($pattern, 0, $target), '.') !== false) {
		return 0;
	}
	if (substr($pattern, $target, 1) === '#') {
		return 0;
	}

	return solutions(substr($pattern, $target + 1), array_slice($counts, 1), $cache);
}

function solutions(string $pattern, array $counts, array &$cache): int {
	$key = "$pattern|" . implode('|', $counts);
	if (isset($cache[$key])) {
		return $cache[$key];
	}

	return $cache[$key] = generate_solutions($pattern, $counts, $cache);
}
