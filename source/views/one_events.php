<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'events';
$this_id = grabString('id');

include 'shots/entities/events.php';

$events = eventsFetch( $this_id );

$all_html = '';

if (empty($events)) {
  $all_html = "No records found.";
} else {
  foreach ($events as $event_id => $data) {
    if (empty($data)) {
      $all_html .= "Event id #" . $event_id ." has no data.";
    } else {
      foreach ( $data as $key => $value ){
        $html = eventsCreateFieldHtml($key, $value);
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

$related_entities = relationshipsFetch('events', $this_id, 'php');

?>

<body>

  <?php include 'html_navbar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div id="main-entity" class="col-md-8">
        <div class="form-horizontal">
          <div class="record" data-entity-name="events">
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

  <script type="text/javascript">
    $(document).ready(function() {

      $('input').change( ajaxChange );

      $('#delete-button').click( openDeleteModal );

      $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities);

      $('#open-file-upload-modal').on('click', openUploadModal);

    }); // end document ready
    
  </script>
</body>
</html>

