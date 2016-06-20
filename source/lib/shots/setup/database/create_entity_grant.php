<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('grants'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('grants');

  // $drop_result = $shots_schema->dropTable('grants');

  print_r($table);

} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('grants');

  $table->addColumn('grant_id',           'integer', array('notnull' => true, 'autoincrement' => true));  
  $table->addColumn('grant_body',         'string',  array('notnull' => false));
  $table->addColumn('grant_mechanism',    'string',  array('notnull' => false));
  $table->addColumn('grant_number',       'string',  array('notnull' => false));
  $table->addColumn('title',              'string',  array('notnull' => false));
  $table->addColumn('link_to_cfp',        'text',    array('notnull' => false));
  $table->addColumn('link_to_submission', 'text',    array('notnull' => false));
  $table->addColumn('status',             'string',  array('notnull' => false));
  $table->addColumn('date_funding_start', 'date',    array('notnull' => false));
  $table->addColumn('date_funding_end',   'date',    array('notnull' => false));
  $table->addColumn('amount',             'decimal', array('notnull' => false, 'precision' => 11, 'scale' => 2));

  $table->setPrimaryKey(array('grant_id'));

  $table->addUniqueIndex(array('grant_body', 'grant_mechanism', 'grant_number'));

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    //print_r($r);
  }
}

echo '<pre>';
print_r(get_defined_vars());
echo '</pre>';

?>