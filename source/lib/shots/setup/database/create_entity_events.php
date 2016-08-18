<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('events'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";
  print_r($shots_schema->listTableDetails('events'));

  // $r = $shots_schema->dropTable('events');
  // print_r($r);


} else {
  echo 'Creating table...';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('events');

  // this is the database id number
  $table->addColumn('event_id',           'integer', array('notnull' => true, 'autoincrement' => true));

  // this is the id number used by fullcalendar to identify repeating events
  $table->addColumn('repeat_id',       'integer', array('notnull' => false));

  $table->addColumn('title',          'string',  array('notnull' => false));
  $table->addColumn('datetime_start', 'string',  array('notnull' => false));
  $table->addColumn('datetime_end',   'string',  array('notnull' => false));
  $table->addColumn('all_day',        'boolean', array('notnull' => false));
  $table->addColumn('type',           'string',  array('notnull' => false));
  $table->addColumn('note',           'text',    array('notnull' => false));

  $table->setPrimaryKey(array('event_id'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    //print_r($r);
  }
}

?>