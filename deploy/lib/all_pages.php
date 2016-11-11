<?php


// handle includes paths on multiple dev/test/prod environments
// this assumes that all_pages.php is in this location:
// <webroot>/<approot>/lib/all_pages.php
// we will extract the <approot> portion:
$doc_root = $_SERVER['DOCUMENT_ROOT'];
$all_pages_directory = dirname(__FILE__);

if ( ($doc_root . DIRECTORY_SEPARATOR . 'lib') === $all_pages_directory ) {
  // this means that shots is running from the <webroot>, we can assume that there is no <approot>
  $app_root = '';
} else {
  $app_root_pattern = "/[\/\\\\]([\\.a-z0-9_-]+)[\/\\\\]lib/i";
  $matches = array();
  preg_match($app_root_pattern, $all_pages_directory, $matches);
  if ( !empty($matches[1]) ){
    // since we found a 
    $app_root = '/' . $matches[1];
  } else {
    $app_root = '';
  }
}

$old_include_path = get_include_path();
$app_include_folder = $doc_root . $app_root . '/includes';
$for_preg = '/' . preg_quote($app_include_folder, '/') . '/';
if ( preg_match($for_preg, $old_include_path) !== 1 ){
  set_include_path($old_include_path . PATH_SEPARATOR . $app_include_folder);
}
// $new_include_path = get_include_path();

$old_include_path = get_include_path();
$app_lib_folder = $doc_root . $app_root . '/lib';
$for_preg2 = '/' . preg_quote($app_lib_folder, '/') . '/';
if ( preg_match($for_preg2, $old_include_path) !== 1 ){
  set_include_path($old_include_path . PATH_SEPARATOR . $app_lib_folder);
}


// Auto class loader for Doctrine stuff
// http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/class-loading.html#usage
use Doctrine\Common\ClassLoader;
require 'Doctrine/Common/ClassLoader.php';

// leaving off the second parameter means that Doctrine will default to using the php include_path
$common_loader = new ClassLoader('Doctrine\Common');
$common_loader->register();

$dbal_loader = new ClassLoader('Doctrine\DBAL');
$dbal_loader->register();

// create a $db variable to hold a connection to the SQLite database
require_once('database_connection.php');

// add utility functions
require_once('functions_utility.php');

// add class for file uploads
require_once('jQuery-File-Upload/UploadHandler.php');

// include the global settings
require_once('shots/internals/settings_global.php');


// authentication
// require_once('shots/internals/authenticate.php');

// TODO cookies, if needed


// start the session
// alternatively clear the session by sending session.php?logout=true
require_once('shots/internals/sessions.php');

if ( $_SERVER['SCRIPT_NAME'] !== '/' . $sign_in_page ){
  if (empty($username)) {
    header('Location: '. $app_root . '/' . $sign_in_page);
    exit;
  }
}






?>