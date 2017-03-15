<?php

include 'lib/all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('grants'));

echo '<pre>';


  //echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('foo');

  $table->addColumn('grant_id',           'integer', array('columnDefinition' => 'INTEGER PRIMARY KEY AUTOINCREMENT'));  
  $table->addColumn('grant_body',         'string',  array('notnull' => false));
  $table->addColumn('grant_mechanism',    'string',  array('notnull' => false));
  $table->addColumn('grant_number',       'string',  array('notnull' => false));

  $table->setPrimaryKey(array('grant_id'));

  $table->addUniqueIndex(array('grant_body', 'grant_mechanism', 'grant_number'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    print_r($r);
  }

// echo '<pre>';
print_r(get_defined_vars());
echo '</pre>';

?>