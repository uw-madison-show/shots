<?php

include_once '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// query a table and get all the data in json;
include 'shots/entities/documents.php';

$all_docs = documentsFetchAll('native', FALSE);

?>



<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 page-content">

      <div class="row form-group">
        <div class="col-xs-4 col-xs-offset-2">
          <label for="file-manager-btn-group-active">Display files:</label>
          <div id="file-manager-btn-group-active" class="btn-group" role="group" data-toggle="buttons" aria-label="Show all files?">
            <label class="btn btn-default">
              Active<input type="radio" name="file-manager-button-active" id="active-files" data-files-to-display="file-manager-doc-active" autocomplete="off">
            </label>
            <label class="btn btn-default">
              Inactive<input type="radio" name="file-manager-button-active" id="active-files" data-files-to-display="file-manager-doc-inactive" autocomplete="off">
            </label>
            <label class="btn btn-default">
              All<input type="radio" name="file-manager-button-active" id="all-files" data-files-to-display="file-manager-doc" autocomplete="off">
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <label for="file-manager-text-filter" aria-label="File name text filter?">Filename filter:</label>
          <input id="file-manager-text-filter" type="text" class="form-control" placeholder="begin typing...">
        </div>
      </div>

      <div class="row">
        <div class="col-md-8 col-md-offset-2">
      
          <table id="file-manager-table" class="table table-condensed table-hover">
            <thead>
              <th width="35"></th>
              <th class="text-primary">File</th>
              <th width="89" class="text-primary text-right">Size</th>
              <th class="text-primary text-right">Upload date</th>
              <th class="text-primary text-right">Version</th>
              <th class="text-primary"></th>
            </thead>

            <tbody>
              
              <?php
                foreach ($all_docs as $key => $doc) {
                  $doc_active_class = ($doc['active'] == 1) ? 'file-manager-doc-active' : 'file-manager-doc-inactive';
                  echo '
                    <tr id="file-manager-doc-id-'. $doc['document_id'] . '" class="file-manager-doc '. $doc_active_class .
                    '" data-entity-name="documents"'.
                    '  data-entity-id="'. $doc['document_id'] .
                    '" data-entity-filename="'. htmlentities($doc['name']) .
                    '" data-entity-extension="'. htmlentities($doc['extension']) .
                    '" data-entity-size="'. htmlentities($doc['size']) .'">
                      <td><span class="glyphicon '.choose_icon($doc['extension']).'"></span></td>
                      <td><a href="' . $doc['url'] .'" target="_blank" download>'. htmlentities($doc['name']. '.'. $doc['extension']) .'</td>
                      <td class="text-right">'. format_bytes($doc['size']) .'</td>
                      <td class="text-right"><small>'. $doc['upload_timestamp'] .'</small></td>
                      <td class="text-right"><small>'. $doc['version'] . '</td>
                      <td>
                        <button class="file-manager-action-button file-manager-button-view btn btn-xs btn-default">
                          <span class="glyphicon glyphicon-eye-open"></span>
                        </button>
                  ';
                  if ($doc['active'] == 1) {
                    echo '
                      <button class="file-manager-action-button file-manager-button-delete btn btn-xs btn-warning">
                        <span class="glyphicon glyphicon-remove"></span>
                      </button>
                    ';
                  } else {
                    echo '
                      <button class="file-manager-action-button file-manager-button-activate btn btn-xs btn-primary">
                        <span class="glyphicon glyphicon-plus"></span>
                      </button>
                    ';
                  }
                  echo '
                      </td>
                    </tr>
                  ';
                }
              ?>

            </tbody>

          </table>
        </div>
      </div>

      <div id="autosave-message-holder"></div>

      <div id="autosave-error-message-holder"></div>
    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">

  $(document).ready(function() {

    // TODO I have to write the click handlers for the view, delete, and activate buttons; in the process i'll need to make /views/one_documents.php?id=%; probably need a modal confirmation dialog for activate/delete.
    
    /**********************************************************/

    // Event Listeners

    /**********************************************************/

    // show all files buttons
    $('#file-manager-btn-group-active :input').on('change', function(e){
      // console.log('clicked!');
      // console.log($(this).data('filesToDisplay'));
      
      // first blank out the text filter
      $('#file-manager-text-filter').val('');

      // hide everything
      $('#file-manager-table tbody tr').hide();
      // show the rows that match the class name
      var to_show = $(this).data('filesToDisplay');
      $('#file-manager-table tbody tr.' + to_show).show();
    });

    // filename text filter
    // stolen from http://jsfiddle.net/giorgitbs/52ak9/1/
    $('#file-manager-text-filter').on('keyup', function(e){
      var rex = new RegExp($(this).val(), 'i');
      $('#file-manager-table tbody tr').hide();
      $('#file-manager-table tbody tr').filter(function() {
        return rex.test($(this).text());
      }).show();
    });

    // view, deactivate, activate buttons
    $('.file-manager-action-button').on('click', function(){
      foobar = $(this);
      var btn_type = 'view';
      if ($(this).hasClass('file-manager-button-activate')){
        btn_type = 'activate';
      } else if ($(this).hasClass('file-manager-button-delete')) {
        btn_type = 'delete';
      }

      var this_doc = $(this).parents('.file-manager-doc').data();

      if ( !$.isEmptyObject(this_doc) ){
        switch(btn_type) {
          case 'view':
            // TODO maybe make a modal pop up to view the file details instead of loading a new page?
            window.location.href = '' + app_root + '/views/one_documents.php?id=' + this_doc.entityId;
            break;
          case 'activate':
            // set other docs with same name and extension to not active, then activate this doc
            var deact = {};
            deact.target = 'entity';
            deact.action = 'documentsDeactivate';
            deact.table  = 'documents';
            deact.params = [];

            deact.params.push({
                               "name":      this_doc.entityFilename,
                               "extension": this_doc.entityExtension,
                               "size":      this_doc.entitySize
                              });

            console.log(deact);

            $.post(app_root + '/lib/ajax_handler.php', 
              { "request": deact },
              "json"
              )
              .done() 
              .fail( ajaxFailed )
              .always(function(r) {
                        if (r.error === false){
                         console.log(r);
                         activate_step2();
                        } else {
                         ajaxFailed(r);
                        }
                      })
              ;

            // now set this doc to active
            function activate_step2() {
              var activate = {};
              activate.target = 'entity';
              activate.action = 'documentsUpdate';
              activate.table  = 'documents';
              activate.params = [this_doc.entityId,
                                 'active',
                                 '1'];

              console.log(activate);

              $.post(app_root + '/lib/ajax_handler.php', 
                { "request": activate },
                "json"
                )
                .done() 
                .fail( ajaxFailed )
                .always(function(r) {
                          if (r.error === false){
                            console.log(r);
                            location.reload();
                          } else {
                            ajaxFailed(r);
                          }
                        })
                ;
            }
            break;
          case 'delete':
            // deactivate this doc; do not worry about 
            var req = {};
            req.target = 'entity';
            req.action = 'documentsDeactivate';
            req.table  = 'documents';
            req.params = [];

            req.params.push({'document_id': this_doc.entityId});

            console.log(req);

            $.post(app_root + '/lib/ajax_handler.php', 
             { "request": req },
             "json"
             )
             .done() 
             .fail( ajaxFailed )
             .always(function(r) {
                       if (r.error === false){
                        console.log(r);
                        location.reload();
                       } else {
                        ajaxFailed(r);
                       }
                     })
             ;
            break;
          default:
          
        } // end switch btn_type
      } // end if this_doc not empty

    });

    /**********************************************************/

    // Page Setup Code

    /**********************************************************/

    $('#active-files').click();

  });
</script>

</body>
</html>


