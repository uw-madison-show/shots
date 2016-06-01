<?php

include '../../lib/all_pages.php';
include 'functions_database.php';

// we will consider the settings_global table to be the master table
// if there is no existing sqlite database, it will be initialized
// with the settings_global table

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('settings_global'));

echo '<pre>';

if ( $table_exists ){

  echo 'Table already exists.';
  echo "\n";

  $table = $shots_schema->listTableDetails('grants');

  print_r($table);

} else {

  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('settings_global');

  $table->addColumn('setting_id',         'integer', array('notnull' => true, 'autoincrement' => true));  
  $table->addColumn('setting_name',       'string',  array('notnull' => false));
  $table->addColumn('setting_value',      'string',  array('notnull' => false));
  $table->addColumn('setting_active',     'boolean');

  $table->setPrimaryKey(array('setting_id'));

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
echo '</pre>';

?>