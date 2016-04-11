<?php

echo 'hello world';

$db = new PDO( 'sqlite:..\database\shots.sq3', NULL, NULL );
var_dump($db);

echo 'goodbye';
?>