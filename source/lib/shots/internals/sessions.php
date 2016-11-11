<?php

// error_log('hello world');
// $foo = print_r(get_defined_vars(), TRUE);
// error_log($foo);

if (!function_exists('grabString')) {
  require_once '../../functions_utility.php';
}
$logout = grabString('logout');

if ($logout) {
  $callback = '/';
  $callback = grabString('callback');

  // deleting php sessions takes a lot of work
  session_start();
  unset($_SESSION);
  // setcookie("PHPSESSID", "", time() - 100, "/");
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
  }
  session_destroy();

  // clear out the username cookies
  if (isset($_COOKIE["shots-username"])) {
    setcookie("shots-username", "", time() - 100, "/");
    unset($_COOKIE["shots-username"]);
  }

  // redirect
  header('Location: '. $callback);
  exit;
} else {
  session_start();
  // load session variables into variables
  $username = '';
  if ( isset($_SESSION['username']) ){
    $username = $_SESSION['username'];
  }
}


?>