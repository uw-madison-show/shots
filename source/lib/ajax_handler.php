<?php

require_once 'all_pages.php';

if ( !isset($_POST['q']) ){
  echo 'error in the ajax handler';
  return FALSE;
}

$this_request = $_POST['q'];

try {
  // include the correct entity type
  require_once('shots/entities/'. $this_request['table'] . '.php');

  // $call_array = array_merge( array($this_request['action']), $this_request['params'] );
  // $call_string = implode(',', $call_array);

  // run the specified function with the provided params
  $result = call_user_func_array($this_request['action'], $this_request['params'] );

} catch (Exception $e) {
  echo $e->getMessage();
  return FALSE;
}

// i assume that most of my CRUD functions will be returning true/false
// so echoing the result is the same as returning a boolean
echo $result;

// print_r(get_defined_vars());
?>