<?php

if (!function_exists('grabString')) {
  require_once '../../functions_utility.php';
}

if (!isset($app_root)) {
  require_once '../../all_pages.php';
}

$logout = grabString('logout');

if ($logout) {
  session_start();
  session_destroy();
  header('Location: '. $app_root . '/index.php');
} else {
  session_start();
}


?>