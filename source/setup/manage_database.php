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

// make a list of 

echo '<pre>';

echo ' hello world';

try {
  $db = new PDO( 'sqlite:..\database\shots.sq3', NULL, NULL );
  $db->exec('create table grants ('.
            'id integer primary key, '.
            'breed text, '.
            'age integer '.
            ')'
            );
  $db->exec('insert into grants (breed, age) values ("husky", 14);'.
            'insert into grants (breed, age) values ("fido", 2);'
            );
} catch (PDOException $e) {
  echo 'Exception: ' . htmlspecialchars($e->getMessage(), ENT_COMPAT, 'UTF-8');
}

$db = NULL;

echo ' goodbye';
?>