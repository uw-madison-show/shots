<?php

if (!isset($app_root)) {
  require_once '../../all_pages.php';
}

//echo 'authentication';
// loop through possible authentication providers
foreach ($authentication_services as $auth_service => $auth_settings) {
  $a = $auth_settings;
  // I can assume that the widget file is already written relative to the <app_root>
  require($auth_settings['widget']);

  if ( !empty($_SESSION['username']) ) { break; }
}


// if all else fails just set the username to anonymous
// $_SESSION['username'] = 'anonymous';
// include 'username.php';

?>