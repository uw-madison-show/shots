<?php

// if you want to customize your own settings, you should comment out
// this line and then write all of your settings in this file;

if (!isset($app_root)) {
  include_once '../../app_root.php';
}

include_once 'default_settings.php';

// You need to change this if you install SHOTS in another timezone; this is used for file uploads and calendar records
$tz = 'America/Chicago';
$shots_default_timezone = new DateTimeZone($tz);
date_default_timezone_set($tz);

// name of the sign in/out pages
// relative to the <approot>; no leading/trailing slashes
$sign_in_page  = 'sign_in.php';
$sign_out_page = 'lib/shots/internals/sessions.php?logout=true&callback='. urlencode($app_root . '/' . $sign_in_page);


// an array called authentication_services will be created in the default_settings file
// if you want override or extend the auth service settings do it here
// Set up API keys or credentials for auth services
$authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';
$authentication_services['google_signin_for_websites']['token_endpoint'] = 'https://www.googleapis.com/oauth2/v4/token';


// change these arrays for your own dev/test/prod servers
$servers['development'] = array('127.0.0.1:8080', 'localhost:3000');
$servers['test']        = array('wwwtest.show.wisc.edu');
$servers['production']  = array('show.wisc.edu');

// this will pick out the server type
$server_type = '';
if ( in_array($_SERVER['HTTP_HOST'], $servers['development']) ){
  $server_type = 'development';
} else if ( in_array($_SERVER['HTTP_HOST'], $servers['test']) ){
  $server_type = 'test';
} else if ( in_array($_SERVER['HTTP_HOST'], $servers['production']) ){
  $server_type = 'production';
}


// change up any of the settings you want to change based on which server this is running at
// e.g. change your authentication credentials and upload directories


/**********************************************************************/

// DEVELOPMENT SERVER SETTINGS

/**********************************************************************/
if ( $server_type === 'development' ){
  $authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';
  $authentication_services['google_signin_for_websites']['secret_file'] = 'C:\\\Users\\\moehr\\\Documents\\\GitHub\\\client-secrets\\\client_secret_test_20161102.json';
  $authentication_services['google_signin_for_websites']['redirect_uri'] = 'http://localhost:3000';



/**********************************************************************/

// TEST SERVER SETTINGS

/**********************************************************************/
} else if ( $server_type === 'test' ){
  $authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';
  $authentication_services['google_signin_for_websites']['secret_file'] = '/var/www/vhosts/wwwtest.show.wisc.edu/private/php_sess/client_secret_test_20161102.json';
  $authentication_services['google_signin_for_websites']['redirect_uri'] = 'https://wwwtest.show.wisc.edu';

  $file_upload_options['upload_dir'] = '/var/www/vhosts/wwwtest.show.wisc.edu/httpdocs/shots/database/files/';
  $file_upload_options['upload_url'] = 'wwwtest.show.wisc.edu/database/files/';




/**********************************************************************/

// PRODUCTION SERVER SETTINGS

/**********************************************************************/
} else if ( $server_type === 'production' ){

  echo 'production server not yet configured in settings_global.php';

} else {
  trigger_error('I was unable to determine what type of server SHOTS is trying to run on. (Dev/Test/Production?) Maybe you should take a look at the settings in ' . __FILE__);
}

