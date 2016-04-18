<?php

include './lib/all_pages.php';
include './lib/doctype.php';
include './lib/head.php';

$new_include_path = get_include_path();


?>

<body>

<?php


$db->insert( 'grants', array('breed' => 'collie', 'age' => '12') );

$sql = 'select * from grants';
$q = $db->query($sql);

while ( $row = $q->fetch() ) {
  print_r($row);
}

?>

<!-- TODO put javascript here -->

</body>

<?php

include './lib/html_footer.php';

?>


