<?php

include 'lib/all_pages.php';
include 'functions_database.php';

try{
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
    header('Location: ' . $app_root . '/views/table_all_' . $first_entity . '.php');
    exit();
  } else {
    // TODO maybe present the user with an error message?
    header('Location: /');
    exit();
  }
} catch (Exception $e) {
  trigger_error('Error deleting records.');
}

// print_r(get_defined_vars());
// TODO include all the html stuff to show an empty page with the potential of having an error message.
// If all goes according to plan this page will have already redirected to another page so this "empty" page should never really be seen by anyone.

?>