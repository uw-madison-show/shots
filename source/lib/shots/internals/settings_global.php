<?php

// if you want to customize your own settings, you should comment out
// this line and then write all of your settings in this file;

include_once 'shots/setup/default_settings.php';

// You need to change this if you install SHOTS in another timezone; this is used for file uploads and calendar records
date_default_timezone_set('America/Chicago');

// an array called authentication_services will be created in the default_settings file
// if you want override or extent the auth service settings do it here
// Set up API keys or credentials for auth services
// TODO test if i am on dev/test/prod and change google client id accordingly
$authentication_services['google_signin_for_websites']['client_id'] = '146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com';

