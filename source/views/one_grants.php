<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'grants';
$this_id = grabString('id');

// include './lib/all_pages.php';
include 'shots/entities/grants.php';

$my_grants = grantsFetch( array($this_id) );

$all_html = '';

if (!empty($my_grants)){
  foreach ($my_grants as $grant_id => $data) {
    if (empty($data)){
      $all_html = "No records found.";
    } else {
      foreach ($data as $key => $value) {
        $html = grantsCreateFieldHtml($key, $value);
        $all_html .= $html;
      }
      // add a delete button
      $all_html .= '<div class="row">
                      <div class="col-xs-2 col-xs-offset-10">
                        <div class="form-group"> 
                          <button type="button" class="btn btn-default" id="delete-button">Delete</button>
                        </div>
                      </div>
                    </div>
                  ';
    }
  }
}

include_once 'shots/relationships/relationships.php';

$related_entities = relationshipsFetch('grants', $grant_id, 'php');

?>



<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div id="main-entity" class="col-md-8">
      <div class="form-horizontal">
        <div class="record" data-entity-name="grants">
          <div class="fields">
          <?php echo $all_html; ?>
          </div>
          <div id="upload-area">
            <?php include 'widget_upload_documents.php' ?>
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

<!-- include javascript scripts -->
<script type="text/javascript">
  $(document).ready(function() {
    console.log('ready');

    $('input').change( ajaxChange );

    $('#delete-button').click( openDeleteModal );

    $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities );

    $('#open-file-upload-modal').on('click', openUploadModal);

  }); // end document ready
</script>
</body>
</html>