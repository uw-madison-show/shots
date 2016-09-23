<?php
// you should not edit this file
// if you want to make custom settings, edit the file at <app_root>/lib/shots/intrnals/settings_global.php
// we assume that the current file is located at <app_root>/lib/shots/setup/default_settings.php

if (!isset($app_root)) {
  require_once '../../all_pages.php';
}

// echo 'default_settings';

// array of the entities with their associtaed setups, functions and configs
$entities = array(
                  'grants' => array('table_creation' => $app_root . '/lib/shots/setup/database/create_entity_grants.php',
                                    'php_functions' => '/lib/shots/entities/grants.php',
                                    ),
                  'people' => array('table_creation' => $app_root . '/lib/shots/setup/database/create_entity_people.php',
                                    'php_functions' => '/lib/shots/entities/people.php',
                                    ),
                  'documents' => array('table_creation' => $app_root . '/lib/shots/setup/database/create_entity_documents.php',
                                    'php_functions' => '/lib/shots/entities/documents.php',
                                    ),
                  'events' => array('table_creation' => $app_root . '/lib/shots/setup/database/create_entity_events.php',
                                    'php_functions' => '/lib/shots/entities/events.php',
                                    ),
                  );

// array of authentication services
// each auth service needs its own SHOTS widget; widget files are relative to <app_root>/lib/
// most auth services will need an API key or credential thing; credentials and API keys should be set in the global settings file because they may differe between dev/test/prod servers and they will change with each SHOTS install
$authentication_services = array(
                                 'google_signin_for_websites' => array('widget' => 'widget_google_signin.php',
                                                                       'client_id' => 'xxxxxxxx.apps.googleusercontent.com'),
                                 // add 
                                 )

?>