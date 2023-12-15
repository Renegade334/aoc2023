<?php

require('common.php');

$input = file_get_contents('input');
$lines = explode("\n", trim($input));

$total = 0;
foreach ($lines as $line) {
	list($pattern, $groups) = explode(' ', $line);
	$groups = array_map('intval', explode(',', $groups));

	$length = strlen($pattern);
	$unknown = substr_count($pattern, '?');
	$seeking = array_sum($groups) - substr_count($pattern, '#');
	$arrangements = 0;

	for ($mask = 0; $mask < 2 << ($unknown - 1); $mask++) {
		if (popcount($mask) !== $seeking) {
			continue;
		}

		$gears = $pattern;
		$pos = 0;
		for ($i = 0; $i < $length; $i++) {
			if ($gears[$i] === '?') {
				$gears[$i] = ($mask & (1 << $pos++)) ? '#' : '.';
			}
		}

		if (array_map('strlen', array_values(array_filter(explode('.', $gears)))) === $groups) {
			$arrangements++;
		}
	}

	$total += $arrangements;
}

echo "$total\n";
