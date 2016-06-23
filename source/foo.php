<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/entities/grants.php';

$json_string = fetchAllGrants( 'json' );



?>



<body>

<div id="table_holder"></div>



<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  var example_data = [
    {id: 1, number: 77, text: "general"},
    {id: 3, number: -1, text: "tso"},
    {id: 4, number: 88, text: "chicken would be"},
    {id: null, number: null, text: null}
    ];

  var table_el = document.querySelector('#table_holder');
  var table_parent_el = table_el.parentNode;

  var table_settings = {
    data: example_data,
    columns: [
      {
        data: 'id',
        type: 'numeric'
      },
      {
        data: 'number',
        type: 'numeric'
      },
      {
        data: 'text',
        type: 'text'
      }
    ],
    colHeaders: [ 'ID', 'Number', 'Text' ],
    rowHeaders: true,
    columnSorting: true,
    sortIndicator: true
  }

  var handson = new Handsontable(table_el, table_settings);
</script>

</body>
</html>


