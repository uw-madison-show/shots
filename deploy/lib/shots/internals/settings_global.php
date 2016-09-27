<?php

// if you want to customize your own settings, you should comment out
// this line and then write all of your settings in this file;

include_once 'shots/setup/default_settings.php';

// You need to change this if you install SHOTS in another timezone; this is used for file uploads and calendar records
date_default_timezone_set('America/Chicago');

// an array called authentication_services will be created in the default_settings file
// if you want override or extend the auth service settings do it here
// Set up API keys or credentials for auth services
$authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';


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
if ( $server_type === 'development' ){
  $authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';

} else if ( $server_type === 'test' ){
  $authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';

  $file_upload_options['upload_dir'] = '/var/www/vhosts/wwwtest.show.wisc.edu/httpdocs/shots/database/files/';
  $file_upload_options['upload_url'] = 'wwwtest.show.wisc.edu/database/files/';

} else if ( $server_type === 'production' ){

  echo 'production server not yet configured in settings_global.php';

} else {

}

