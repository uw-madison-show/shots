<?php
// you should not edit this file
// if you want to make custom settings, edit the file at <app_root>/lib/shots/intrnals/settings_global.php
// we assume that the current file is located at <app_root>/lib/shots/setup/default_settings.php

if (!isset($app_root)) {
  require_once '../../all_pages.php';
}

// echo 'default_settings';

// default settings will go here
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

?>