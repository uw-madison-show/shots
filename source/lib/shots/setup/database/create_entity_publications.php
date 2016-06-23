<?php

include '../../../all_pages.php';

$platform = $db->getDatabasePlatform();
$shots_schema = $db->getSchemaManager();

$table_exists = $shots_schema->tablesExist(array('publications'));

if ( $table_exists ){
  echo 'Table already exists.';
  echo "\n";
} else {
  echo 'Creating table.';

  $schema = new \Doctrine\DBAL\Schema\Schema();

  $table = $schema->createTable('publications');

  $table->addColumn('publication_id',     'integer', array('notnull' => true, 'autoincrement' => true));
  $table->addColumn('external_id_system', 'string',  array('notnull' => false));
  $table->addColumn('external_id_number', 'string',  array('notnull' => false));
  $table->addColumn('title',              'string',  array('notnull' => false));
  $table->addColumn('status',             'string',  array('notnull' => false));

  $table->setPrimaryKey(array('publication_id'));

  $table->addUniqueIndex(array('external_id_system', 'external_id_number'));

  // make lookup table if necessary and add foreign key
  $lookup_external_id_system_exists = $shots_schema->tablesExist(array('lookup_external_id_system'));
  if ( !$lookup_external_id_system_exists ){
    echo 'Creating external_id_system lookup table.';

    $lookup_external_id_system = $schema->createTable('lookup_external_id_system');

    $lookup_external_id_system->addColumn('database_value',    'string',  array('notnull' => true));
    $lookup_external_id_system->addColumn('html_value',        'string',  array('notnull' => false));
    $lookup_external_id_system->addColumn('html_description',  'string',  array('notnull' => false));

    $lookup_external_id_system->setPrimaryKey(array('database_value'));
  }

  $table->addForeignKeyConstraint($lookup_external_id_system, 
                                  array('external_id_system'),
                                  array('database_value'),
                                  array(),
                                  'external_id_system_refernce'
                                  );

  $sql = $schema->toSql($platform);

  foreach ($sql as $this_sql) {
    echo htmlentities($this_sql);
    $ddl = $db->prepare($this_sql);
    $ddl->execute();
    $r = $ddl->fetchAll();
  }
}

?>