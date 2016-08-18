<?php

// TODO add local fallbacks for all the cdn libs

echo '
  <head>
    <title>Output Tracking</title>
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
    <script src="/includes/handsontable/handsontable.full.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/handsontable/handsontable.full.min.css">
    ';

// moment js for handling date and time stuff
echo '
    <script src="/includes/moment/moment-2-14-1.min.js"></script>
    ';

// fullcalendar JS and CSS
// from fullcalendar docs: "jQuery and Moment must be loaded before FullCalendar's JavaScript."
echo '
    <link rel="stylesheet" href="/includes/fullcalendar/fullcalendar.css" />
    <script src="/includes/fullcalendar/fullcalendar.js"></script>
    ';

// file upload
// source: https://github.com/blueimp/jQuery-File-Upload
echo '
    <script src="/includes/jQuery-File-Upload/jquery.ui.widget.js"></script>
    <script src="/includes/jQuery-File-Upload/jquery.iframe-transport.js"></script>
    <script src="/includes/jQuery-File-Upload/jquery.fileupload.js"></script>
     ';


// custom JS and CSS for the shots database
echo '
    <script src="/lib/js/shots.js"></script>
    <link rel="stylesheet" type="text/css" href="/lib/css/shots.css">
    ';

echo '</head>';
?>