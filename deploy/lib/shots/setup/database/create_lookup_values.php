<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('lookup_values'));

echo '<pre>';

if ($table_exists) {
  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('lookup_values');
  print_r($table);

} else {
  echo "Creating table ...\n";

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('lookup_values');

  $table->addColumn('lookup_value_id',  'integer', array('notnull' => true, 'autoincrement' => true));
  $table->addColumn('table_name',       'string',  array('notnull' => false));
  $table->addColumn('column_name',      'string',  array('notnull' => false));
  $table->addColumn('lookup_value',     'string',  array('notnull' => false));
  $table->addColumn('label',            'string',  array('notnull' => false));

  $table->setPrimaryKey(array('lookup_value_id'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
  }
}

?>