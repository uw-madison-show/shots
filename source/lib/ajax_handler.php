<?php
header("Content-Type: application/json;charset=utf-8");

/**
 * This page is basically one big function that handles incoming requests and sends back json results.
 *
 * Sometimes ajax_handler.php makes the changes in the database, and other times it calls additional pages to make the changes. E.g. pages like `/lib/shots/setup/database/create_entity_grant.php` has the create table scripts for the grants. All of the params must come in through the $_POST['request'] global var.
 *
 * @param string $target This is one of entity, page, report, relationship, setting. The target determines which of the other paramters are optional or required.
 * @param string $action When target is "entity" then this field is required. It is the name of the function to be called. E.g. "updateGrant".
 * @param string $table When the target is one of [ entity | report | relationship | setting ] this param is required. It denotes the database table name that will be acted upon.
 * @param array $params When action is specified, this param is required. It is an arrray of string/number values for paramters of the function specified in action.
 * @param string $page When targe is "page" this param is required. It should be an URL relative to SHOTS home directory. E.g. "/lib/shots/setup/database/create_entity_grant.php"
 *
 * @return string json object of format
 *           {
 *            error: boolean,
 *            error_messages: [ 'string1', ..., 'stringN' ],
 *            request_params: {action: 'string',
 *                             page: 'string',
 *                             table: 'string',
 *                             target: 'string'
 *                             },
 *            results: [ <objects that depend on the request type> ]
 *           }
 */


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
                               'relationships',
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
      $i = include('shots/setup/database/'. $this_request["page"] . '.php');
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
} elseif ( $this_request["target"] === 'relationships' ) {
  // this will handle most of the CRUD functions for relationship table
  try {
    // include the correct entity type
    include_once('shots/relationships/relationships.php');
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
} elseif ( $this_request["target"] === 'setting' ) {
  # code...
} else {
  // return error
}


echo json_encode($return_array);
// exit();
?>