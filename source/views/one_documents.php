<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'documents';
$this_id = grabString('id');

include 'shots/entities/documents.php';

// third param only_show_active_documents = FALSE; 
$documents = documentsFetch( $this_id, 'php', FALSE );

$all_html = '';

if (empty($documents)) {
  $all_html = "No records found.";
} else {
  foreach ($documents as $document_id => $data) {
    if (empty($data)) {
      $all_html .= "Document id #" . $document_id ." has no data.";
    } else {
      foreach ( $data as $key => $value ){
        $html = documentsCreateFieldHtml($key, $value);
        $all_html .= $html;
      }
    }
  }
}

include_once 'shots/relationships/relationships.php';

$related_entities = relationshipsFetch('documents', $this_id, 'php');

?>

<body>

  <?php include 'html_navbar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div id="main-entity" class="col-md-8">
        <div class="form-horizontal">
          <div class="record" data-entity-name="documents">
            <div class="fields">
              <?php echo $all_html; ?>
            </div>
          </div>
        </div>

      </div>
      <div id="related-entities" class="col-md-3">
        <div id="related-entities-accordian" class="panel-group">
          <?php include 'widget_related_entities.php' ?>
        </div>
      </div>
    </div>

  <?php include 'html_footer.php'; ?>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {

      $(':input').change( ajaxChange );

      $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities);

      // TODO the active checkbox should mimic the function of the activate button found on the manager_all_documents.php page; ie it should active this doc and deactivate all the other versions of this doc

    }); // end document ready
    
  </script>
</body>
</html>

