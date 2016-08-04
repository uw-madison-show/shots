<?php

include_once '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/entities/grants.php';

?>



<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 page-content">
      <div id="table-holder" data-entity-name="grants">
        <button id="button-add-row" type="button" class="btn btn-default" data-ajax="false">+ Add Row</button>
        <!-- TODO add a "Delete this Row" button. Maybe use context menu? -->
      </div>

      <div id="autosave-message-holder"></div>

      <div id="autosave-error-message-holder"></div>
    </div>
  </div>
</div>

<?php
// $table_name = 'grants';
// $edits = array('title' => 'my new title',
//                'status' => 'done'
//                );
// $key_field = 'grant_id';
// $id = '12';

// $key_field = $db->quoteIdentifier($key_field);

// $results = array();

// foreach ($edits as $field => $value) {
//     $f = $db->quoteIdentifier($field);
//     $qb = $db->createQueryBuilder();
//     $qb
//       ->select($field)
//       ->from($table_name)
//       ->where($key_field . ' = ?' )
//       // ->setParameter(0, $key_field)
//       ->setParameter(0, $id)
//       ;
//     $stmt = $qb->execute();
//     $res = $stmt->fetchColumn(0);
//     $results[] = $res;
// }

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


