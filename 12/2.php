<?php

require('common.php');

$input = file_get_contents('input');
$lines = explode("\n", trim($input));

$cache = [];
$total = 0;
foreach ($lines as $line) {
	list($pattern, $groups) = explode(' ', $line);
	$pattern = implode('?', array_fill(0, 5, $pattern));
	$groups = array_merge(...array_fill(0, 5, array_map('intval', explode(',', $groups))));

	$total += solutions($pattern, $groups, $cache);
}

echo "$total\n";
