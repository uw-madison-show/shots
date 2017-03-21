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

      $('.datepicker').datetimepicker({format: 'YYYY-MM-DD h:mm a',
                                       extraFormats: ['YYYY-MM-DD HH:mm:ss',
                                                      'YYYY-MM-DD HH:mm',
                                                      'YYYY-MM-DD h:mm a',
                                                      'YYYY-MM-DD h a',
                                                      'YYYY-MM-DD',
                                                      'M/D/YY',
                                                      'M/D/YYYY',
                                                      'MM/DD/YYYY',
                                                      'M/D/YY h:mm a',
                                                      'M/D/YYYY h:mm a',
                                                      'MM/DD/YYYY h:mm a',
                                                      'M/D/YY h a',
                                                      'M/D/YYYY h a',
                                                      'MM/DD/YYYY h a',
                                                      ],
                                       sideBySide: true,
                                       defaultDate: $(this).val()
                                       });

      $('.datepicker').on('dp.show', function(e){
        // console.log(e);
        // keep track of the changes done in the datepicker dialog
        var most_recent_change_event = {};
        $(this).on('dp.change', function(change_event){
          most_recent_change_event = change_event;
        });
        // fire a database update when the dialog closes; send in the last change we have
        $(this).on('dp.hide', function(e2){
          // console.log('hide');
          // console.log(e2);
          if ( most_recent_change_event ){
            ajaxChange(most_recent_change_event);
          }
        });
      });

      $('#datetime_start, #datetime_end').on('focusout', ajaxChange);

      $(':input').change( ajaxChange );

      $('#delete-button').click( openDeleteModal );

      $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities);

      $('#open-file-upload-modal').on('click', openUploadModal);

    }); // end document ready
    
  </script>
</body>
</html>

