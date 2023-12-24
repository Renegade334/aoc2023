<?php

require('common.php');

$input = file_get_contents('input');
$map = new NodeMap($input);

echo $map->walk(64, false) . "\n";
