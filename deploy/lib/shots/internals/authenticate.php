<?php
error_log('server side authentication script');
session_start();
require_once('settings_global.php');
$return_array = array();


// Google Project: show-output-tracking-system
// Google owner: moehr@wisc.edu
// credential: test SHOTS - Survey of the Health of Wisconsin Output Tracking System
// authorized JS origins:
  // https://wwwtest.show.wisc.edu
  // http://localhost:3000

/************************************************************************
** approach #2 where i tried to use the "Authenticate with Backend Server" thing
** https://developers.google.com/identity/sign-in/web/backend-auth

// $get_url = $authentication_services['google_signin_for_websites']['token_endpoint'] . '?' . 'id_token=' . urlencode($_POST['id_token']);

// echo $get_url;
// $get_url = "http://www.purple.com";

// $curl_options = array(CURLOPT_URL => $get_url,
//                       // CURLOPT_HTTPGET => TRUE,
//                       CURLOPT_RETURNTRANSFER => TRUE,
//                       CURLOPT_HEADER => FALSE,
//                       );

// $curl = curl_init();
// curl_setopt_array($curl, $curl_options);
// $curl_result = curl_exec($curl);
// $curl_error = curl_error($curl);
// curl_close($curl);

// $return_array[] = $curl_result;
// // phpinfo();
// print_r(get_defined_vars());

/***********************************************************************/


// check that i have a google id and email
if ( !isset($_POST['Id']) || !isset($_POST['Email']) || !isset($_POST['code']) ){

  // this is kind of a hail mary to see if the session or cookie already exists, if it does we use it and give up further auth work
  if ( isset($_SESSION['username']) ){
    setcookie("shots-username", $_SESSION['username'],  time()+60*60*24*7, '/');
    $return_array['error']  = 'false';
    $return_array['error_messages'][] = 'Missing auth info, but we already had a session variable so carry on then.';
  } else if ( isset($_COOKIE['shots-username']) ){
    $_SESSION['username'] = $_COOKIE['shots-username'];
    $return_array['error']  = 'false';
    $return_array['error_messages'][] = 'Missing auth info, but we already had a cookie so carry on then.';
  } else {
    $return_array['error']  = 'true';
    $return_array['error_messages'][] = 'Missing the id, email, or auth code from google.';
    $return_array['error_messages'][] = $_POST;  
  }
} else {
  $auth_code = $_POST['code'];

  // read in the client secret from the json file
  // secret file is downloaded from google api manager
  // file is just a json object but it should be stored outside of <app_root>
  $secret_file = file_get_contents($authentication_services['google_signin_for_websites']['secret_file']);

  $secret_array = json_decode($secret_file, TRUE);

  if (!isset($authentication_services['google_signin_for_websites']['token_endpoint']) ||
      !isset($authentication_services['google_signin_for_websites']['client_id']) || 
      !isset($secret_array['web']['client_secret']) ){
    $return_array['error'] = 'true';
    $return_array['error_messages'][] = 'authenticate.php needs token enpoint, client id, and client secret to authenticate with google';
    $return_array['error_messages'][] = 'token endpoint = ' . $authentication_services['google_signin_for_websites']['token_endpoint'];
    $return_array['error_messages'][] = 'client id = ' . $authentication_services['google_signin_for_websites']['client_id'];
    $return_array['error_messages'][] = 'length of client secret = ' . strlen($secret_array['web']['client_secret']);

  } else {
    $token_request_fields = array('code' => $auth_code,
                                  'client_id' => $authentication_services['google_signin_for_websites']['client_id'],
                                  'client_secret' => $secret_array['web']['client_secret'],
                                  'redirect_uri' => $authentication_services['google_signin_for_websites']['redirect_uri'],
                                  'grant_type' => 'authorization_code',
                                  );

    $token_request_fields_encoded = http_build_query($token_request_fields);

    $post_options = array(CURLOPT_URL => $secret_array['web']['token_uri'],
                          CURLOPT_POST => TRUE,
                          CURLOPT_POSTFIELDS => $token_request_fields_encoded,
                          // CURLOPT_HTTP_VERSION => 1.0,
                          CURLOPT_RETURNTRANSFER => TRUE,
                          CURLOPT_HEADER => FALSE,
                          // CURLINFO_HEADER_OUT => TRUE,
                          );

    // pass auth code to google server
    $post_request = curl_init();    
    curl_setopt_array($post_request, $post_options);
    $post_result = curl_exec($post_request);
    $post_result_parsed = json_decode($post_result, TRUE);
    // var_dump($post_result_parsed);  

    if ( !isset($post_result_parsed['access_token']) ){
      $return_array['error'] = 'true';
      $return_array['error_messages'][] = 'access_token is not set';
      $return_array['error_messages'][] = curl_error($post_request);
      $return_array['error_messages'][] = curl_getinfo($post_request);
      $return_array['error_messages'][] = $post_result_parsed;
    } else {
      
      $return_array['error'] = 'false';
      $return_array['result'] = $post_result_parsed;

      // update session var and cookie
      $_SESSION['username'] = $_POST['Email'];
      setcookie("shots-username", $_SESSION['username'], time()+60*60*24*7, '/');

    }

    curl_close($post_request);


  } // end if i have client_id and client_secret

} // end if i have a auth code

// $bar = print_r(get_defined_vars(), TRUE);
// error_log($bar);



$foo = json_encode($return_array);
echo $foo;
return TRUE;
?>