<?php

include_once '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include_once 'shots/entities/people.php';

?>

<body>


<!-- This div will eventually hold the table.
     The Add Row button must go inside the table-holder div.
     You can use as many data- attributes as you want for meta-data or parameters or whathave you.
     The data-entity-name attribute is required, so js functions know which table to manipulate.
-->

<div id="table-holder" data-entity-name="people" data-key-field="email">
  <button id="button-add-row" type="button" class="btn btn-default" data-ajax="false">+ Add Row</button>
</div>



<!-- TODO add a "Delete this Row" button. Maybe use context menu? -->



<div id="autosave-message-holder"></div>

<div id="autosave-error-message-holder"></div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">

  $(document).ready(function() {

    /**********************************************************/

    // Page Setup Code

    /**********************************************************/

    initializeTable('people', 'person_id');

    /**********************************************************/

    // Event Listeners

    /**********************************************************/

    $("#button-add-row").on( 'click', addRow );

  });
</script>

</body>
</html>


