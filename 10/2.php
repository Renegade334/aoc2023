<?php

require_once('common.php');

$input = file_get_contents('input');
$map = new NodeMap($input);

echo sprintf("%d\n", count($map->getEnclosedNodes()));
