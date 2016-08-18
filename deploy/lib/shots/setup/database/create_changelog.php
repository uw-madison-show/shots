<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('changelog'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";

  // echo 'Dropping table...';
  // $shots_schema->dropTable('changelog');

  $table = $shots_schema->listTableDetails('changelog');
  print_r($table);


} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('changelog');

  $table->addColumn('change_id',          'integer',   array('notnull' => true, 'autoincrement' => true));  
  $table->addColumn('table_name',         'string',    array('notnull' => false));
  $table->addColumn('key_field',          'string',    array('notnull' => false));
  $table->addColumn('key_value',          'string',    array('notnull' => false));
  $table->addColumn('field',              'string',    array('notnull' => false));
  $table->addColumn('old_value',          'text',      array('notnull' => false));
  $table->addColumn('new_value',          'text',      array('notnull' => false));
  $table->addColumn('change_username',    'string',    array('notnull' => false));
  $table->addColumn('change_timestamp',   'datetime',  array('columnDefinition' => 'timestamp DEFAULT CURRENT_TIMESTAMP'));

  $table->setPrimaryKey(array('change_id'));

  $sql = $schema->toSql($platform);

  // print_r($sql);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    // print_r($ddl);
    $ddl->execute();
    $r = $ddl->fetchAll();
    //print_r($r);
  }
}

// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';

?>