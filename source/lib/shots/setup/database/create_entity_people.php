<?php

include_once '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('people'));

echo '<pre>';

if ( $table_exists ){

  // echo 'Dropping Table';

  // $result = $shots_schema->dropTable('people');

  // var_dump($result);

  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('people');

  print_r($table);


} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('people');

  $table->addColumn('person_id', 'integer', array('notnull' => true,
                                                  'autoincrement' => true
                                                  ) 
                    );
  $table->addColumn('email', 'string', array('notnull' => false));
  $table->addColumn('name', 'string', array('notnull' => false));
  $table->addColumn('address', 'text', array('notnull' => false));
  $table->addColumn('phone', 'string', array('notnull' => false));
  $table->addColumn('affiliation', 'text', array('notnull' => false));
  $table->addColumn('category', 'string', array('notnull' => false));
  $table->addColumn('is_primary_investigator', 'boolean', array('notnull' => false));

  $table->setPrimaryKey(array('person_id'));

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