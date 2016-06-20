<?php
include 'lib/all_pages.php';
include 'functions_database.php';

include 'html_doctype.php';

include 'html_head.php';


// make a list of the tables that go into shots
// TODO move this into the settings table
$shots_tables = array('settings_global',
                      'settings_shots',
                      'relationships',
                      'people',
                      'grants',
                      'events'
                      );

// check if tables exists / how many rows they have

// user input for re/creating new tables, downloading data, etc.

// remember that a schema *manager* is not the schema class itselt
// schema managers have a whole different set of methods/properties
$shots_schema = $db->getSchemaManager();

$shots_database_exists = sqliteDatabaseExists($shots_schema);

// var_dump($shots_database_exists);




echo '<body>';

include 'html_container.php';

if ( !$shots_database_exists ) {
  // do something to create the database
  echo '<div class="col-xs-12 main">';
  echo '<div>Hey. There is no database. Do you want to make one?</div>';
  echo '
    <div class="btn-group">
      <button type="button" class="btn btn-default" data-ajax="true" data-ajax-target="page" data-ajax-page="create_settings_global">Create SHOTS!</button>
    </div>
  ';
  echo '</div>';


} else {
  // present the options for managing tables

  foreach ($shots_tables as $table_name) {
    echo '<div class="info-table">' . $table_name;

    $table_exists = $shots_schema->tablesExist($table_name);

    // var_dump($table_exists);

    if ( $table_exists ){

      $table_info = $shots_schema->listTableDetails($table_name);

      echo '
        <div class="info-table-detials">
          <pre>
            ' . print_r($table_info) .'
          </pre>
        </div>
      ';

      /*
      // Column info
      $columns = $shots_schema->listTableColumns($table);
      echo '
        <div class="info-table-column-set">
          # of Columns: '. count($columns)
      ;
      foreach ($columns as $column) {
        echo '
          <div class="info-table-column">' . 
            $column->getName() . ' ('. $column->getType() .') 
          </div>
        ';
      }
      echo '</div>'; // close info-table-column-set

      // Key info
      $keys["indexes"] = 
      */

    } else {
      echo '
        <div class="info-table-dne">
          Does not exist. 
          <button type="button" class="btn btn-default" data-ajax="true" data-ajax-target="page" data-ajax-page="/lib/shots/setup/database/create_entity_grant.php">
            Create it?
          </button>
        </div>
      ';
    }
    echo '</div>'; // close info-table
  }

} // end if shots database exists

?>

<script type="text/javascript">

  $('button').on('click', function(e){
    e.preventDefault();
    // console.log($(this).data());
    var button_data = $(this).data();
    if ( button_data.ajax ){
      // do ajax post

      var req = {};

      req.target   = button_data.ajaxTarget ? button_data.ajaxTarget : '';
      req.action   = button_data.ajaxAction ? button_data.ajaxAction : '';
      req.table    = button_data.ajaxTable  ? button_data.ajaxTable  : '';
      req.params   = button_data.ajaxParams ? button_data.ajaxParams : [];
      req.page     = button_data.ajaxPage   ? button_data.ajaxPage   : '';

      console.log(req);

      $.post('/lib/ajax_handler.php', { "request": req })
         .done( function(d){
          console.log(d);

         })
         .fail( 
           function(d){
             console.log('ajax post failed');
             console.log(d);
           }
         )
         .always()
         ;

      // return result
    } else {

    }
  });

  // todo code to display the result message from the ajax post
  // todo code to display errors of ajax post

</script>

</body>

<?php include 'html_footer.php'; ?>