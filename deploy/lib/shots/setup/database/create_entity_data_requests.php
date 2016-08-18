<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('data_requests'));

if ( $table_exists ){
  echo 'Table already exists.';
  echo "\n";
} else {
  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('data_requests');

  $table->addColumn('data_request_id',    'integer', array('notnull' => true, 'autoincrement' => true));  
  $table->addColumn('date_started',       'date',    array('notnull' => false));
  $table->addColumn('date_delivered',     'date',    array('notnull' => false));
  $table->addColumn('title',              'string',  array('notnull' => false));

  $table->setPrimaryKey(array('data_request_id'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
  }
}

?>