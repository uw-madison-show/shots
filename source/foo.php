<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/entities/grants.php';

$json_string = fetchAllGrants( 'json' );



?>



<body>

<div id="table-holder"></div>

<div id="autosave-message-holder"></div>



<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  // var example_data = [
  //   {id: 1, number: 77, text: "general"},
  //   {id: 3, number: -1, text: "tso"},
  //   {id: 4, number: 88, text: "chicken would be"},
  //   {id: null, number: null, text: null}
  //   ];

  var autosave_timeout;

  function saveChange(change) {
    clearTimeout(autosave_timeout);

    console.log(change);

    var row      = change[0];
    var col_name = change[1];
    var old_val  = change[2];
    var new_val  = change[3];

    var database_id = example_data[row][example_data_key_field];

    var req = {};
    req.target = 'entity';
    req.action = 'updateGrant';
    req.table = 'grants';
    req.params = [];

    req.params.push(database_id);
    req.params.push(col_name);
    req.params.push(new_val);

    console.log(req);

    $.post('/lib/ajax_handler.php', 
             { "request": req },
             "json"
             )
             .done(
                   function(d){
                     console.log('ajax post done');
                     console.log(d);
                   }) 
             .fail(
                   function(d){
                     console.log('ajax post fail');
                     console.log(d);
                   })
             .always(
                     function(d) {
                       console.log('ajax always');
                     })
             ;

  }

  var example_data = <?php echo $json_string; ?>;

  var example_data_fields = Object.keys(example_data[0]);

  var example_data_key_field = 'grant_id';

  // console.log(example_data_fields);

  var table_cols = [];

  example_data_fields.forEach(function (field) {
    // console.log(field);
    if ( field === example_data_key_field ){
      table_cols.push({data: field,
                       type: 'numeric',
                       editor: false
                       });
    } else {
      table_cols.push({data: field});
    }
  })

  var table_el = document.querySelector('#table-holder');
  var table_parent_el = table_el.parentNode;

  var table_settings = {
    data: example_data,
    columns: table_cols,
    colHeaders: example_data_fields,
    rowHeaders: true,
    columnSorting: true,
    sortIndicator: true,
    fixedColumnsLeft: 1,
    maxRows: example_data.length,
    afterChange: function (changes, source) {
      console.log(source);
      console.log(changes);
      var edit_types = [ 'alter', 'empty', 'edit', 'autofill', 'paste', 'external' ];
      if ( edit_types.indexOf(source) > -1 ) {
        for ( var i = 0; i < changes.length; i++ ){
          saveChange(changes[i]);
        }
      } else {
        console.log('ignoring table change: ' + changes);
      }
    }
  }

  var handson = new Handsontable(table_el, table_settings);
</script>

</body>
</html>


