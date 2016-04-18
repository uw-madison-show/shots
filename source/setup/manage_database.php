<?php



// database types supported by shots
// should be taken from the list of PDO drivers: http://php.net/manual/en/pdo.drivers.php
// and formatted as the DSN prefix, e.g.: http://php.net/manual/en/ref.pdo-sqlite.connection.php
$shots_databases = array('sqlite');

// make a list of the tables that go into shots
$shots_tables = array('shots_settings',
                      'relationships',
                      'people',
                      'grants',
                      'events'
                      );

// check if tables exists / how many rows they have

// user input for re/creating new tables, downloading data, etc.


include '../lib/all_pages.php';

$dbal_config = new Doctrine\DBAL\Configuration();
$db_connection_settings = array('driver' => 'pdo_sqlite',
                                'path' => $_SERVER['DOCUMENT_ROOT'] . '\database\shots.sq3'
                                );
try {
  $db = \Doctrine\DBAL\DriverManager::getConnection($db_connection_settings,
                                                    $dbal_config
                                                    );
} catch (Exception $e) {
  echo 'Exception: ' . htmlspecialchars($e->getMessage(), ENT_COMPAT, 'UTF-8');
}

$manager = $db->getSchemaManager();

$tables = $manager->listTables();

foreach ($tables as $table) {
  echo $table->getName() . " columns: \n\n";
  foreach ($table->getColumns() as $column) {
    echo '<div>';
    print_r($column);
    echo '</div>';
  }
}


echo '<pre>';
print_r(get_defined_vars());
echo ' goodbye';
?>