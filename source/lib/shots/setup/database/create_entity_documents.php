<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('documents'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";
  print_r($shots_schema->listTableDetails('documents'));
  // $r = $shots_schema->dropTable('documents');
  // print_r($r);

} else {
  echo "Creating table...\n";

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('documents');

  $table->addColumn('document_id', 'integer', array('notnull' => true, 'autoincrement' => true));
  // this is the filename as it is stored on the server
  $table->addColumn('server_name', 'string',  array('notnull' => false));
  $table->addColumn('name',        'string',  array('notnull' => false));
  $table->addColumn('extension',   'string',  array('notnull' => false));
  $table->addColumn('size',        'string',  array('notnull' => false));
  $table->addColumn('mime_type',   'string',  array('notnull' => false));
  $table->addColumn('url',         'text',    array('notnull' => false));
  $table->addColumn('title',       'string',  array('notnull' => false));
  $table->addColumn('description', 'text',    array('notnull' => false));
  $table->addColumn('version',     'integer', array('notnull' => false));
  $table->addColumn('active',      'boolean', array('notnull' => false));

  $table->setPrimaryKey(array('document_id'));

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