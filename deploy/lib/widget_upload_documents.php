<?php

include_once 'shots/entities/documents.php';
include_once 'shots/relationships/relationships.php';

$related_files_html = '';

if ( empty($this_table) or empty($this_id) ){
  trigger_error('Document upload widget could not get data for either the table name or the entity id.');
} else {

  // get the related files
  $related_files = array();
  if ( !empty($related_entites) ){
    if ( !empty($related_entites['documents']) ){
      $related_files = $related_entites['documents'];
    } 
  } else {
    $related_entites = relationshipsFetch($this_table, $this_id);
    if ( !empty($related_entites['documents']) ){
      $related_files = $related_entites['documents'];
    }
  }

  // loop through related files and build a list of id numbers
  $related_documents_id_array = array();
  foreach ($related_files as $key => $this_file) {
    $related_documents_id_array[] = $this_file['id'];
  }

  $related_documents = documentsFetch($related_documents_id_array);

  // related documents could be empty if all the docs are set to inactive
  if (!empty($related_documents)){
    // build up the html for displaying download links
    foreach ($related_documents as $key => $this_document) {
      $related_files_html .= '<div class="related-documents">';
      $related_files_html .= '<a href="'. $this_document['url'] .'">'. $this_document['name'] .'</a>';
      $related_files_html .= '</div>';
    }
  }
}




  
echo '
    <div class="form-group">
      <div class="col-xs-2">
        <button id="open-file-upload-modal" type="button" class="btn btn-default">Attach file...</button>
      </div>
      <div class="col-xs-10">
        '. $related_files_html .'
      </div>
    </div>
    ';

/*
// TODO make the upload button work as a drag and drop target area
// TODO move all of this code into javascript. the .fileupload() initializer is having too many glitches with the hidden div + clone into a modal approach that I started using
echo '
    <div id="file-upload-modal-message" style="display: none;">
      <div class="form-horizontal">
        <p>Upload file</p>

        <div class="form-group">
          <label class="control-label col-xs-4" for="title">Title</label>
          <div class="col-xs-8">
            <input id="title" name="title" class="form-control" type="text" placeholder="optional" />
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-xs-4" for="description">Description</label>
          <div class="col-xs-8">
            <input id="description" name="description" class="form-control" type="text" placeholder="optional" />
          </div>
        </div>

        <input type="hidden" name="from_entity_type" id="from_entity_type" value="' . $this_table . '"/>
        <input type="hidden" name="from_entity_id" id="from_entity_id" value="' . $this_id . '"/>

        <div class="form-group">
          <div class="col-xs-4 col-xs-offset-4">
            <label for="file-upload-button" class="btn btn-default">Pick file<input type="file" id="file-upload-button" name="files[]" data-url="lib/file_handler.php"  style="display: none;"/>
            </label>
          </div>
        </div>
      </div>
    </div>
    ';
*/
  
  
?>

