<?php

require_once 'all_pages.php';

$dbal_config = new Doctrine\DBAL\Configuration();
$db_connection_settings = array('driver' => 'pdo_sqlite',
                                'path' => $_SERVER['DOCUMENT_ROOT'] . $app_root . '/database/shots.sq3'
                                );
try {
  $db = \Doctrine\DBAL\DriverManager::getConnection($db_connection_settings,
                                                    $dbal_config
                                                    );
} catch (Exception $e) {
  echo 'Exception: ' . htmlspecialchars($e->getMessage(), ENT_COMPAT, 'UTF-8');
}

?>