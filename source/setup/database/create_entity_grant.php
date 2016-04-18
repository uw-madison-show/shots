<?php

include '../../lib/all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('grants'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('grants');

  print_r($table);

  // $drop_result = $shots_schema->dropTable('grants');


} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('grants');

  $table->addColumn('grant_uid', 'guid');  
  $table->addColumn('grant_body', 'string');
  $table->addColumn('grant_mechanism', 'string');
  $table->addColumn('grant_number', 'string');
  $table->addColumn('title', 'string');

  $table->addColumn('link_to_cfp', 'text');
  $table->addColumn('link_to_submission', 'text');

  $table->addColumn('status', 'string');

  $table->addColumn('date_funding_start', 'date');
  $table->addColumn('date_funding_end', 'date');
  $table->addColumn('amount', 'decimal', array('precision' => 11, 'scale' => 2));

  $table->setPrimaryKey(array('grant_uid'));

  $table->addUniqueIndex(array('grant_body', 'grant_mechanism', 'grant_number'));

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