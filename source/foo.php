<!DOCTYPE html>
<html>
<head>
  <title>hi</title>
  <link rel="stylesheet" type="text/css" href="lib/shots_styles.css">
</head>
<body>

<?php 

// phpinfo();

try {
  $db = new PDO('sqlite:database\shots.sq3', NULL, NULL);
} catch (PDOException $e) {
  echo 'Exception: ' . htmlspecialchars($e->getMessage(), ENT_COMPAT, 'UTF-8');
}

$r = $db->query('select * from grants');

echo '<pre>';

print_r($r);

foreach ($r as $row) {
  print_r($row);
}

$db = NULL;

print_r(get_defined_vars());

echo '</pre>';



?>

</body>
</html>