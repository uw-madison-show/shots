<?php

include './lib/all_pages.php';
include './lib/doctype.php';
include './lib/head.php';

$new_include_path = get_include_path();


?>

<body>

<?php

$dbal_config = new Doctrine\DBAL\Configuration();

$pdo = new PDO( 'sqlite:database\shots.sq3', NULL, NULL );

// $db_connection_settings = array('pdo' => $pdo);

$db_connection_settings = array('driver' => 'pdo_sqlite',
                                'path' => 'database\shots.sq3'
                                );

try {
  $db_conn = \Doctrine\DBAL\DriverManager::getConnection($db_connection_settings,
                                                         $dbal_config
                                                         );
} catch (Exception $e) {
  echo 'Exception: ' . htmlspecialchars($e->getMessage(), ENT_COMPAT, 'UTF-8');
}


$sql = 'select * from grants';
$q = $db_conn->query($sql);

while ( $row = $q->fetch() ) {
  print_r($row);
}

?>

<!-- TODO put javascript here -->

</body>

<?php

include './lib/html_footer.php';

?>


