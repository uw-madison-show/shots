<?php
include '../lib/all_pages.php';
include 'functions_database.php';

include 'html_doctype.php';

include 'html_head.php';


// make a list of the tables that go into shots
$shots_tables = array(//'settings_global',
                      //'settings_shots',
                      //'relationships',
                      'people',
                      'grants',
                      //'events'
                      );

// check if tables exists / how many rows they have

// user input for re/creating new tables, downloading data, etc.

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
      <button type="button" class="btn btn-default" data-ajax-target="page" data-ajax-page="create_settings_global">Create SHOTS!</button>
    </div>
  ';
  echo '</div>';


} else {
  // present the options for managing tables

  // foreach ($shots_tables as $table) {
  //   echo $table . '</br>';

  //   $table_exists = $shots_schema->tablesExist($table);

  //   var_dump($table_exists);

    // if ( $table_exists ){
    //   foreach ($table->getColumns() as $column) {
    //     echo '<div>';
    //     print_r($column);
    //     echo '</div>';
    //   }
    // } else {
    //   echo 'does not exist. Create it?';
    // }
  // }

} // end if shots database exists

?>

<script type="text/javascript">
  // make some code to listen for button presses
</script>

</body>

<?php include 'html_footer.php'; ?>