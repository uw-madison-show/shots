<?php

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

?>
