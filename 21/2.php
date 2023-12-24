<?php

require('common.php');

$input = file_get_contents('input');
$map = new NodeMap($input);

echo $map->walk(26501365, true) . "\n";
