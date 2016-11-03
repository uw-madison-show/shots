<?php

// authenticate with google sign in
require_once('../../all_pages.php');
$return_array = array();

$return_array[] = $_POST;

// Google Project: show-output-tracking-system
// Google owner: moehr@wisc.edu
// credential: test SHOTS - Survey of the Health of Wisconsin Output Tracking System
// authorized JS origins:
  // https://wwwtest.show.wisc.edu
  // http://localhost:3000

$get_url = $authentication_services['google_signin_for_websites']['token_endpoint'] . '?' . 'id_token=' . urlencode($_POST['id_token']);

// echo $get_url;

// $get_url = "http://www.purple.com";

$curl_options = array(CURLOPT_URL => $get_url,
                      // CURLOPT_HTTPGET => TRUE,
                      CURLOPT_RETURNTRANSFER => TRUE,
                      CURLOPT_HEADER => FALSE,
                      );

$curl = curl_init();
curl_setopt_array($curl, $curl_options);
$curl_result = curl_exec($curl);
$curl_error = curl_error($curl);
curl_close($curl);

$return_array[] = $curl_result;
// phpinfo();
print_r(get_defined_vars());
// // test for session variables for this user
// if ( isset($_SESSION['username']) ){
//   setcookie("shots-username", $_SESSION['username'],  time()+60*60*24*7);
//   return true;
// }

// if ( isset($_COOKIE['shots-username']) ){
//   $_SESSION['username'] = $_COOKIE['shots-username'];
//   return true;
// }


// // if no session vars

// // check that i have a authorization code from the js client
// if ( !isset($_POST['code']) ){
//   $return_array['error'] = 'true';
//   $return_array['result'] = 'No google auth code available in the authentication.php file';
// }

// if ( isset($_POST['code']) ){

//   $auth_code = $_POST['code'];

//   // read in the client secret from the json file
//   $secret_file = file_get_contents($authentication_services['google_signin_for_websites']['secret_file']);

//   $secret_array = json_decode($secret_file, TRUE);

//   if (!isset($authentication_services['google_signin_for_websites']['token_endpoint']) ||
//       !isset($authentication_services['google_signin_for_websites']['client_id']) || 
//       !isset($secret_array['web']['client_secret']) ){
//     $return_array['error'] = 'true';
//     $return_array['result'] = 'Could not find token endpoint, client id, or client secret in authenticate.php';
//   } else {

//     $token_request_fields = array('code' => $auth_code,
//                                   'client_id' => $authentication_services['google_signin_for_websites']['client_id'],
//                                   'client_secret' => $secret_array['web']['client_secret'],
//                                   // 'redirect_uri' => 'www.google.com',
//                                   'grant_type' => 'authorization_code',
//                                   );

//     $token_request_fields_encoded = http_build_query($token_request_fields);

//     $post_options = array(CURLOPT_URL => $authentication_services['google_signin_for_websites']['token_endpoint'],
//                           CURLOPT_POST => TRUE,
//                           CURLOPT_POSTFIELDS => $token_request_fields_encoded,
//                           CURLOPT_HTTP_VERSION => 1.0,
//                           CURLOPT_RETURNTRANSFER => TRUE,
//                           CURLOPT_HEADER => TRUE,
//                           CURLINFO_HEADER_OUT => TRUE,
//                           );

//     // pass auth code to google server
//     $post_request = curl_init();
    
//     curl_setopt_array($post_request, $post_options);

//     $post_result = curl_exec($post_request);

//     var_dump(curl_getinfo($post_request));

//     print_r(get_defined_vars());


//     if ( empty($post_result) ){
//       $return_array['error'] = 'true';
//       $return_array['request'] = $post_request;
//       $return_array['result'] = $post_result;
//       $return_array['curl_error_message'] = curl_error($post_request);
//     } else {
//       // TODO test that my app client id is part of the auth
//       // TODO update session vars

//       $return_array['error'] = 'false';
//       $return_array['result'] = $post_result;
//     }

//   } // end if i have client_id and client_secret

// } // end if i have a auth code

$foo = json_encode($return_array);
// echo $foo;
return TRUE;
?>