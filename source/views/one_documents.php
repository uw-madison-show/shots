<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'documents';
$this_id = grabString('id');

include 'shots/entities/documents.php';

$documents = documentsFetch( $this_id );

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
      // documents can not be deleted
      // TODO add a "deactivate" button for documents
      // $all_html .= '<div class="row">
      //                 <div class="col-xs-2 col-xs-offset-10">
      //                   <div class="form-group"> 
      //                     <button type="button" class="btn btn-default" id="delete-button">Delete</button>
      //                   </div>
      //                 </div>
      //               </div>
      //             ';
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

      $('input').change( ajaxChange );

      $('#delete-button').click( openDeleteModal );

      $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities);

    }); // end document ready
    
  </script>
</body>
</html>

