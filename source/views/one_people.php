<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'people';
$this_id    = grabString('id');

include_once 'shots/entities/people.php';

$results = peopleFetch( array($this_id) );

$all_html = '';

foreach ($results as $people_id => $data) {
  foreach ($data as $key => $value) {
    $html = peopleCreateFieldHtml($key, $value);
    $all_html .= $html;
  }
}

include_once 'shots/relationships/relationships.php';

$related_entities = relationshipsFetch('people', $people_id, 'php');

?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div id="main-entity" class="col-md-8">
      <div class="form-horizontal">
        <div class="record" data-entity-name="people">
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
</div> <!-- close container-fluid -->

<script type="text/javascript">
  $(document).ready(function() {
    console.log('ready');

    $(':input').change( ajaxChange );

    $('#delete-button').click( openDeleteModal );

    $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities );

    $('#open-file-upload-modal').on('click', openUploadModal);

  }); // end document ready
</script>

</body>
</html>