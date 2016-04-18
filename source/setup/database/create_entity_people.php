<?php

include '../../lib/all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('people'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('people');

  print_r($table);


} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('people');

  $table->addColumn('email', 'string');
  $table->addColumn('name', 'string');
  $table->addColumn('address', 'text');
  $table->addColumn('phone', 'string');
  $table->addColumn('affiliation', 'text');
  $table->addColumn('category', 'string');
  $table->addColumn('is_primary_investigator', 'boolean');

  $table->setPrimaryKey(array('email'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    print_r($r);
  }
}

// echo '<pre>';
// print_r(get_defined_vars());
echo '</pre>';
?>