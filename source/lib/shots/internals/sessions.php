<?php

session_start();

// TODO make a login page
// include('login.php');

if ( !isset($_SERVER['Shib_Session_ID']) ) {
  trigger_error('No UW NetID authentication detected.');
} else {
  $u = $_SESSION['username'];
  if (!$u) {
    // try to get shibboleth credential first
    if ( isset($_SERVER['REMOTE_USER']) ) {
      $_SESSION['username'] = $_SERVER['REMOTE_USER'];
    } else if ( isset($_SERVER['eppn']) ) {
      $_SESSION['username'] = $_SERVER['eppn'];
    } else if ( isset($_SERVER['uid']) ) {
      $_SESSION['username'] = $_SERVER['uid'];
    } else {
      // TODO If shibboleth id is not present, try to get some sort of outside ID. Google or twitter maybe?
      trigger_error('Unable to determine UW NetID. Though we do have a session ID. So that is strange. Someday in the near future this will prompt you to login via twitter/Google/something.');
    }
  }
  // read the current username or "anonymous" into a global var
  $username = $_SESSION['username'] ? $_SESSION['username'] : 'anonymous';
}

?>