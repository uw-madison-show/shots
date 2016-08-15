<?php

include_once '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include_once 'shots/entities/people.php';

?>

<body>

<?php include 'html_navbar.php'; ?>

<!-- This div will eventually hold the table.
     The Add Row button must go inside the table-holder div.
     You can use as many data- attributes as you want for meta-data or 
     parameters or whathave you.

     The data-entity-name and data-target attributes are required, so 
     js functions know which table to manipulate.
-->

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 page-content">
      <div id="table-holder" data-target="entity" data-entity-name="people">
        <button id="button-add-row" type="button" class="btn btn-default" data-ajax="false">+ Add Row</button>
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

    initializeTable('entity', 'people', 'person_id');

    /**********************************************************/

    // Event Listeners

    /**********************************************************/

    $("#button-add-row").on( 'click', addRow );

  });
</script>

</body>
</html>


