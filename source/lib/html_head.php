<?php

require_once 'all_pages.php';

// TODO add local fallbacks for all the cdn libs

echo '
    <head>
    <title>Output Tracking</title>
    ';  

// these are php server vars that need to be set as 'global' type javascript vars
echo '
    <script>
        var app_root = "' . $app_root .'";
        var doc_root = "' . $doc_root .'";
    </script>
    ';


// JQuery from Google cdn
echo '
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    ';

// Bootstrap JS and CSS from MaxCDN
echo '
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    ';
    
// Font Awesome from MaxCDN
echo '
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    ';
    
// Handsontable from local
// see: https://github.com/handsontable/handsontable
echo '
    <script src="'. $app_root . '/includes/handsontable/handsontable.full.min.js"></script>
    <link rel="stylesheet" type="text/css" href="'. $app_root .'/includes/handsontable/handsontable.full.min.css">
    ';

// moment js for handling date and time stuff
echo '
    <script src="'. $app_root .'/includes/moment/moment-2-14-1.min.js"></script>
    ';

// fullcalendar JS and CSS
// from fullcalendar docs: "jQuery and Moment must be loaded before FullCalendar's JavaScript."
echo '
    <link rel="stylesheet" href="'. $app_root .'/includes/fullcalendar/fullcalendar.css" />
    <script src="'. $app_root .'/includes/fullcalendar/fullcalendar.js"></script>
    ';

// file upload
// source: https://github.com/blueimp/jQuery-File-Upload
echo '
    <script src="'. $app_root .'/includes/jQuery-File-Upload/jquery.ui.widget.js"></script>
    <script src="'. $app_root .'/includes/jQuery-File-Upload/jquery.iframe-transport.js"></script>
    <script src="'. $app_root .'/includes/jQuery-File-Upload/jquery.fileupload.js"></script>
    ';

// Google sign-in for websites
echo '
    <script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
    <meta name="google-signin-client_id" content="146936374460-leoa054enovpuksq875b9ignedeqnhsr.apps.googleusercontent.com">
    ';


// custom JS and CSS for the shots database
echo '
    <script src="'. $app_root .'/lib/js/shots.js"></script>
    <link rel="stylesheet" type="text/css" href="'. $app_root .'/lib/css/shots.css">
    ';

echo '</head>';
?>