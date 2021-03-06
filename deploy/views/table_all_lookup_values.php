<?php

include_once '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/internals/lookup_values.php';

?>



<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 page-content">
      <div id="table-holder" data-target="internals" data-entity-name="lookup_values">
        <button id="button-add-row" type="button" class="btn btn-default" data-ajax="false">+ Add Row</button>
        <!-- TODO add a "Delete this Row" button. Maybe use context menu? -->
      </div>

      <div id="autosave-message-holder"></div>

      <div id="autosave-error-message-holder"></div>
    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">

  $(document).ready(function() {
    


    /**********************************************************/

    // Page Setup Code

    /**********************************************************/

    initializeTable('internals', 'lookup_values', 'lookup_value_id');

    /**********************************************************/

    // Event Listeners

    /**********************************************************/

    $("#button-add-row").on( 'click', addRow );

  });
</script>

</body>
</html>


