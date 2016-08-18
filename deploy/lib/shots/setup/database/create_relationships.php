<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('relationships'));

// echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('relationships');
  print_r($table);


} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('relationships');

  $table->addColumn('relationship_id',    'integer',   array('notnull' => true, 'autoincrement' => true));  
  $table->addColumn('from_entity_type',   'string',    array('notnull' => false));
  $table->addColumn('from_entity_id',     'integer',   array('notnull' => false));
  $table->addColumn('relationship_type',  'string',    array('notnull' => false));
  $table->addColumn('to_entity_type',     'string',    array('notnull' => false));
  $table->addColumn('to_entity_id',       'integer',   array('notnull' => false));

  $table->setPrimaryKey(array('relationship_id'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    //print_r($r);
  }
}

// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';

?>