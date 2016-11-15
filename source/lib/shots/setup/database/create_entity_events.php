<?php

include '../../../all_pages.php';

$table_name = 'events';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array($table_name));

$delete_table = grabString('delete');

echo '<pre>';

// var_dump($delete_table);

if ( $table_exists ){

  echo '<br><br>'. $table_name . ' table already exists.';

  echo '<br><br><a href="' . $app_root .'/manage_database.php">< Return to database manager.</a>';

  echo '<br><br><a href="' . $_SERVER['PHP_SELF'] .'?delete=true">Click here to delete the table.</a>';

  echo '<br><br>You should probably <a href="'. $app_root . '/includes/phpLiteAdmin/phpliteadmin.php?table='. $table_name .'&action=table_export">export a copy of the data</a> before deleting the table.';

  if ( $delete_table ) {

    echo '<br><br>Deleting table...';

    $drop_sql = 'DROP TABLE ' . $table_name;
    $ddl = $db->prepare($drop_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    print_r($r);

  }

} else {

  echo 'Creating table...<br>';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable($table_name);

  // this is the database id number
  $table->addColumn('event_id',       'integer', array('columnDefinition' => 'INTEGER PRIMARY KEY AUTOINCREMENT'));

  // this is the id number used by fullcalendar to identify repeating events
  $table->addColumn('repeat_id',      'integer', array('notnull' => false));

  $table->addColumn('title',          'string',  array('notnull' => false));
  $table->addColumn('datetime_start', 'string',  array('notnull' => false));
  $table->addColumn('datetime_end',   'string',  array('notnull' => false));
  $table->addColumn('all_day',        'boolean', array('notnull' => false));
  $table->addColumn('type',           'string',  array('notnull' => false));
  $table->addColumn('show_presenter', 'string',  array('notnull' => false));
  $table->addColumn('audience',       'string',  array('notnull' => false));
  $table->addColumn('note',           'text',    array('notnull' => false));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    print_r($r);
  }
}

echo '</pre>';
?>