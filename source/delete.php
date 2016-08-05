<?php

include 'lib/all_pages.php';
include 'functions_database.php';

$deletions = json_decode(urldecode($_POST['delete-array']));

$at_least_one_deletion = FALSE;

if (!empty($deletions)){
  $first_entity = $deletions[0][0];
  foreach ($deletions as $key => $this_delete) {
    $result = deleteRecord($this_delete[0], $this_delete[1], $this_delete[2]);
    if ($result){
      $at_least_one_deletion = TRUE;
    }
  }
}

if ($at_least_one_deletion) {
  header('Location: /views/table_all_' . $first_entity . '.php');
  exit();
} else {
  // TODO maybe present the user with an error message?
  header('Location: /');
  exit;
}

print_r(get_defined_vars());

?>