<?php

function encode_in_place (&$value) {
  $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
}

$foo = array("foo" => array("<script>console.log('gotcha');</script>", 
                            18, 
                            FALSE, 
                            NULL, 
                            TRUE
                            ),
             "bar" => array("<h1>INJECTED</h1>",
                            "hi",
                            "TRUE"
                            ),
             );
echo '<pre>';
echo "\nBefore:";
var_dump($foo);

// encode_in_place($foo);
// array_walk_recursive($foo, 'encode_in_place');

include 'lib/functions_utility.php';

$new = encode($foo);

echo "\nAfter:";
var_dump($new);

echo '</pre>';

?>

