<?php

include_once '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

include_once 'functions_database.php';

$sm = $db->getSchemaManager();

$q = $db->query('select * from changelog;');
$r = $q->fetchAll();

$changelog_fields = $sm->listTableColumns('changelog');

$changelog_field_names = array_map('convertFieldName', array_keys($changelog_fields));

?>



<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 page-content">
      <div id="table-holder" data-entity-name="changelog">
        <table class="table">
          <thead>
            <tr>
              <td></td>
              <?php
                foreach ($changelog_field_names as $key => $value) {
                  echo '<td>' . $value . '</td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($r as $row_number => $fields) {
                echo '<tr>';
                echo '<td>'. $row_number . '</td>';
                foreach (array_keys($changelog_fields) as $this_field) {
                  echo '<td>' . $fields[$this_field] . '</td>';
                }
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
</div>



<?php include 'html_footer.php'; ?>

<script type="text/javascript">

  $(document).ready(function() {
    

  });
</script>

</body>
</html>


