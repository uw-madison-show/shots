<?php
header("Content-Type: application/json;charset=utf-8");

require_once 'all_pages.php';

$return_array = array("request_params" => array(),
                      "error"          => TRUE,
                      "error_messages" => array(),
                      "results"        => array(),
                      );

if ( !isset($_POST['request']) ){
  $return_array["error"] = TRUE;
  $return_array["error_messages"][] = 'No parameters submitted to '. $_SERVER['SCRIPT_NAME'];
  echo json_encode($return_array);
}

$this_request = $_POST["request"];

$return_array["request_params"] = $this_request;

$valid_request_targets = array('entity',
                               'page',
                               'report',
                               'relationship',
                               'setting'
                               );

if ( !in_array($this_request["target"], $valid_request_targets) ){
  $return_array["error"] = TRUE;
  $return_array["error_messages"][] = 'Target must be on of '. implode(', ', $valid_request_targets);
  echo json_encode($return_array);
}

if ( $this_request["target"] === 'entity' ){
  // this will handle most of the CRUD functions for the database
  try {
    // include the correct entity type
    include_once('shots/entities/'. $this_request['table'] . '.php');
    // run the specified function with the provided params
    $result = call_user_func_array($this_request['action'], $this_request['params'] );
    $return_array["error"] = FALSE;
    $return_array["results"][] = $result;
  } catch (Exception $e) {
    $return_array["error"] = TRUE;
    $return_array["error_messages"][] = 'PHP or database error in '. $_SERVER['SCRIPT_NAME'];
    $return_array["error_messages"][] = $e->getMessage();
    $return_array["error_messages"][] = $e->getTraceAsString();
  }
} elseif ( $this_request["target"] === 'page' ) {
  // this will handle, e.g., the pages that create, export, delete tables
  // echo 'i am doing page ajax call';
  try {
    ob_start();
      $i = include('../setup/database/'. $this_request["page"] . '.php');
      $result = ob_get_contents();
    ob_end_clean();
    $return_array["error"] = FALSE;
    $return_array["results"]["message"] = $result;
    $return_array["results"]["include_result"] = $i;
  } catch (Exception $e) {
    $return_array["error"] = TRUE;
    $return_array["error_messages"][] = 'PHP or database error in '. $_SERVER['SCRIPT_NAME'];
    $return_array["error_messages"][] = $e->getMessage();
    $return_array["error_messages"][] = $e->getTraceAsString();
  }

} elseif ( $this_request["target"] === 'report' ) {
  # code...
} elseif ( $this_request["target"] === 'relationship' ) {
  # code...
} elseif ( $this_request["target"] === 'setting' ) {
  # code...
} else {
  // return error
}


echo json_encode($return_array);
// exit();
?>