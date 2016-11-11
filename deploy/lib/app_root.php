<?php

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

?>