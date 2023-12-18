<?php

require_once('common.php');

$input = file_get_contents('input');
$map = new NodeMap($input);

echo $map->traverse(1, 3) . "\n";
