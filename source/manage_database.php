<?php
include 'lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

// TODO move this array into the global settings php file;
$table_setup_scripts = array("Changelog"       => "create_changelog.php",
                             "Data Requests"   => "create_entity_data_requests.php",
                             "Documents"       => "create_entity_documents.php",
                             "Events"          => "create_entity_events.php",
                             "Grants"          => "create_entity_grants.php",
                             "People"          => "create_entity_people.php",
                             "Publications"    => "create_entity_publications.php",
                             "Lookup Values"   => "create_lookup_values.php",
                             "Relationships"   => "create_relationships.php",
                             "Global Settings" => "create_settings_global.php",
                             );

?>

<body>

  <?php include 'html_navbar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-4 col-xs-offset-2">
        <h2><a href="<?php echo $app_root; ?>/includes/phpLiteAdmin/phpliteadmin.php">Open Database Manager</a></h2>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-4 col-xs-offset-2">
        <h2>Create / Delete Tables:</h2>
        <div class="list-group">
          <?php
            foreach ($table_setup_scripts as $key => $val) {
              echo '<a class="list-group-item" href="'. $app_root .'/lib/shots/setup/database/'. $val.'">'. $key .'</a>';
            }
          ?>
        </div>
      </div>
    </div>

    <?php include 'html_footer.php'; ?>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {

    });
  </script>

</body>
</html>
