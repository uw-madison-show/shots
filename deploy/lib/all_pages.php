<?php

// handle includes paths on multiple dev/test/prod environments
$old_include_path = get_include_path();
$doc_root = $_SERVER['DOCUMENT_ROOT'];

$app_include_folder = $doc_root . '/includes';
$for_preg = '/' . preg_quote($app_include_folder, '/') . '/';
if ( preg_match($for_preg, $old_include_path) !== 1 ){
  set_include_path($old_include_path . ';' . $app_include_folder);
}
// $new_include_path = get_include_path();

$old_include_path = get_include_path();
$app_lib_folder = $doc_root . '/lib';
$for_preg2 = '/' . preg_quote($app_lib_folder, '/') . '/';
if ( preg_match($for_preg2, $old_include_path) !== 1 ){
  set_include_path($old_include_path . ';' . $app_lib_folder);
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

require_once('database_connection.php');

// add utility functions
require_once('utility_functions.php');

// TODO session variables

// TODO cookies, if needed

// TODO php error handling

// TODO php ini settings, if needed

// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>