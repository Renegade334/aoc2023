<?php

require('common.php');

$input = file_get_contents('input');
$map = new NodeMap($input);

echo $map->search(true) . "\n";
