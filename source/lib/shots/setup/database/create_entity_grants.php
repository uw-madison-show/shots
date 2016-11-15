<?php

include '../../../all_pages.php';

$table_name = 'grants';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array($table_name));

$delete_table = grabString('delete');

echo '<pre>';

// var_dump($delete_table);

if ( $table_exists ){

  echo '<br><br>'. $table_name . ' table already exists.';

  echo '<br><br><a href="' . $app_root .'/manage_database.php">< Return to database manager.</a>';

  echo '<br><br><a href="' . $_SERVER['PHP_SELF'] .'?delete=true">Click here to delete the table.</a>';

  echo '<br><br>You should probably <a href="'. $app_root . '/includes/phpLiteAdmin/phpliteadmin.php?table='. $table_name .'&action=table_export">export a copy of the data</a> before deleting the table.';

  if ( $delete_table ) {

    echo '<br><br>Deleting table...';

    $drop_sql = 'DROP TABLE ' . $table_name;
    $ddl = $db->prepare($drop_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
    print_r($r);

  }

} else {

  echo 'Creating table...<br>';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable($table_name);

  $table->addColumn('grant_id',           'integer', array('columnDefinition' => 'INTEGER PRIMARY KEY AUTOINCREMENT'));  
  $table->addColumn('grant_body',         'string',  array('notnull' => false));
  $table->addColumn('grant_mechanism',    'string',  array('notnull' => false));
  $table->addColumn('grant_number',       'string',  array('notnull' => false));
  $table->addColumn('title',              'string',  array('notnull' => false));
  $table->addColumn('principal_investigator', 'string', array('notnull' => false));
  $table->addColumn('link_to_cfp',        'text',    array('notnull' => false));
  $table->addColumn('link_to_submission', 'text',    array('notnull' => false));
  $table->addColumn('status',             'string',  array('notnull' => false));
  $table->addColumn('date_funding_start', 'date',    array('notnull' => false));
  $table->addColumn('date_funding_end',   'date',    array('notnull' => false));
  $table->addColumn('amount',             'decimal', array('notnull' => false, 'precision' => 11, 'scale' => 2));

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