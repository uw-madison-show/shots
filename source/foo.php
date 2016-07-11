<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/entities/grants.php';

?>



<body>

<div id="table-holder" data-entity-name="grants"></div>

<button id="button-add-row" type="button" class="btn btn-default" data-ajax="false">+ Add Row</button>

<!-- TODO add a "Delete this Row" button. Maybe use context menu? -->



<div id="autosave-message-holder"></div>

<div id="autosave-error-message-holder"></div>

<?php
$table_name = 'grants';
$edits = array('title' => 'my new title',
               'status' => 'done'
               );
$key_field = 'grant_id';
$id = '12';

$key_field = $db->quoteIdentifier($key_field);

$results = array();

foreach ($edits as $field => $value) {
    $f = $db->quoteIdentifier($field);
    $qb = $db->createQueryBuilder();
    $qb
      ->select($field)
      ->from($table_name)
      ->where($key_field . ' = ?' )
      // ->setParameter(0, $key_field)
      ->setParameter(0, $id)
      ;
    $stmt = $qb->execute();
    $res = $stmt->fetchColumn(0);
    $results[] = $res;
}

print($foobar);

$foobar = 22 / 0;
// print($foobar);

try {
    $db->query('select * from foobar;');
} catch (Exception $e) {
    custom_exception_handler($e);
}

?>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">

  $(document).ready(function() {
    


    /**********************************************************/

    // Page Setup Code

    /**********************************************************/

    initializeTable('grants', 'grant_id');

    /**********************************************************/

    // Event Listeners

    /**********************************************************/

    $("#button-add-row").on( 'click', addRow );

  });
</script>

</body>
</html>


