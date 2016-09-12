<?php

require_once 'all_pages.php';
require_once 'jQuery-File-Upload/UploadHandler.php';
require_once 'functions_database.php';
require_once 'shots/relationships/relationships.php';


$sm                    = $db->getSchemaManager();
$documents_fields      = $sm->listTableColumns('documents');
$documents_primary_key = $sm->listTableIndexes('documents')['primary']->getColumns()[0];

$only_show_active_documents_default = TRUE;

/**
 * Format the bytes into human-readable file size.
 *
 * stolen from https://github.com/ael-code/dir-listing-bootstrap/blob/master/dir_listing_func.php (GNU GPL licensed)
 *
 * @param integer $size The number of bytes in the file.
 *
 * @return string File size with KB, MB, GB, etc. attached.
 */
function format_bytes($size)
{
      
  $sizes = array('&nbsp&nbspB', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $count = 0;
  while( $count < (count( $sizes )-1) and $size > 1024){
    $size = $size/1024;
    $count ++;
  }
  $result = sprintf("%.2f %s", $size, $sizes[$count]);
  return $result;
}

/**
 * Pick an icon for an extension.
 * 
 * stolen from https://github.com/ael-code/dir-listing-bootstrap/blob/master/dir_listing_func.php (GNU GPL licensed)
 *
 * @param string $extension The file extension (defaults to 'txt').
 *
 * @return string The name of the glyphicon icon.
 */
function choose_icon( $extension = 'txt' )
{
  $ext = strtolower($extension); 
  
  $types = array(
  "audio" => array("aif","iff","m3u","m4a","mid","mp3","mpa","ra","wav","wma"),
  "video" => array("avi","mkv","3gp","asf","asx","3g2","flv","m4v","mov","mp4","mpg","rm","srt","swf","vob","wmv"),
  "image" => array("gif","jpg","jpeg","png","psd","pspimage","tga","thm","tif","tiff","yuv","svg","bmp","dds"),
  "text" => array("doc","docx","log","msg","odt","pages","rtf","tex","txt","wpd","wps","pdf"),
  "zip" => array("7z","deb","gz","pkg","rar","rpm",".tar.gz","zip","zipx","jar"),
  "disk" => array("bin","cue","dmg","iso","mdf","toast","vcd"),
  "code" => array("java","c","class","pl","py","sh","cpp","cs","dtd","fla","h","lua","m","sln"),
  "excel" => array("xlr","xls","xlsx")
  );
  $icons = array(
  "audio" => "glyphicon-music",
  "video" => "glyphicon-film",
  "image" => "glyphicon-picture",
  "text" => "glyphicon-file",
  "zip" => "glyphicon-compressed",
  "disk" => "glyphicon-record",
  "code" => "glyphicon-indent-left",
  "excel" => "glyphicon-list-alt",
  "generic" => "glyphicon-unchecked"
  );
  foreach( $types as $elem_i => $elem_v){
    if(in_array($ext, $elem_v)){
      return $icons[$elem_i];
    }
  }
  return $icons['generic'];
}


/**
 * Returns the HTML for a field in the documents table.
 *
 * Takes the field name, field value, and an array of options and returns a string
 * containing the basic HTML for this one field. Strings, integers, etc. all have
 * good default settings for display. This function can be extended to have special
 * display stuff for specific fields. The default is to have an edittable field.
 * If you want readonly or disabled field, send that as an option.
 *
 * @param string $field_name The name of the field you want to display.
 * @param mixed $field_value The value of the field. Can be string, integer, date, etc.
 * @param string[] $options An array of options for the field. E.g. 'readonly'.
 *
 * @return string
 */
function documentsCreateFieldHtml( $field_name = FALSE, $field_value = FALSE, $options = array() )
{
  global $db, $documents_fields;
  if ($field_name === FALSE or $field_value === FALSE) return FALSE; 

  if ( !in_array($field_name, array_keys($documents_fields)) ){
    // TODO error message 
    return FALSE;
  }

  $field_value = encode($field_value);

  $return_html = '';

  // set up opening divs and spans
  $return_html .= '<div class="field">';
  $return_html .= '<div class="form-group">';

  // e.g. drop down lookups
  // TODO make title, description, and active into editable fields
  $special_fields = array();
  if ( in_array($field_name, $special_fields) ){
    // do stuff for speical fields
  } else {
    // do stuff for normal fields

    // set up the label
    $return_html .= '<label class="control-label col-xs-4" for="'. $field_name . '">'. convertFieldName($field_name) .'</label>';
    // figure out if i have integer, string, text, date, etc.
    // based on the DBAL Types
    $field_type = $documents_fields[$field_name]->getType();

    // echo "my field type: ";
    // echo $field_type;
    switch ($field_type) {
      case 'Integer':
      case 'Decimal':
      case 'String':
      case 'Date':
        // add a normal input field for all four types
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<input class="form-control" type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'" readonly/>';
        $return_html .= '</div>';
        break;
        // TODO make date fields a date picker input
      case 'Text':
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<textarea readonly class="form-control" rows="2" id="'. $field_name . '" name="'. $field_name . '">' . $field_value . '</textarea>';
        $return_html .= '</div>';
        break;
      default:
        // TODO add a default
        break;
    } // end switch
  } // end if field name is in special fields

  // apply options

  // close the divs and spans
  $return_html .= '</div>'; // close form-group; 
  $return_html .= '</div>'; // close field;

  return $return_html;

}

//viewGrant

// function for uploading a file
// this is very different than other entities because files have to be physically copied AND the database has to be populated
// adabpted from example at https://github.com/blueimp/jQuery-File-Upload/wiki/PHP-MySQL-database-integration


/**
 * Add record to the documents table.
 *
 * @param string $field_name
 * @param string $field_value
 *
 * @return boolean
 */
function documentsAdd( $field_name = FALSE, $field_value = FALSE )
{
  global $db, $documents_fields;

  if ($field_name === FALSE or $field_value === FALSE) { 
    trigger_error('field name and field value are required');
    return FALSE; 
  }

  if ( !in_array($field_name, array_keys($documents_fields)) ) {
    trigger_error($field_name . ' not found in documents table');
    return FALSE;  
  } 

  return addRecord('documents',
                   $field_name,
                   $field_value
                   );
}

//updateGrant
/**
 * Changes a value in the grant table.
 *
 * You provide the key value, the field name, and the new value.
 * 
 * @param integer $id_value The id value for the grant for the update.
 * @param string $field_name Field to be updated.
 * @param mixed $new_value New value. Can be integer, string, etc. depending on the type of $field_name.
 *
 * @return boolean TRUE when there are no errors and there is an update. FALSE when there are no updates or when there are errors.
 */
function documentsUpdate( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $documents_fields, $documents_primary_key;

  if (!$id_value or !$field_name){
    trigger_error('Missing params for documentsUpdate().');
    return FALSE;
  }

  if ( !in_array($field_name, array_keys($documents_fields)) ){
    trigger_error($field_name .' not in documents table.');
    return FALSE;
  }

  return updateRecord('documents',
                      array($field_name => $new_value),
                      $documents_primary_key,
                      $id_value
                      );
}

/**
 * Returns all of the documents in the table.
 *
 * @param string $return_format The format that the function returs. Can be one of 'json', 'php', or 'csv'. Defaults to 'json'.
 * @param boolean $only_show_active_documents Determines if the database returns all matching documents or just the documents where active = '1'. Defaults to TRUE.
 *
 * @return mixed JSON string or php array depending on the value of $return_format.
 */
function documentsFetchAll( $return_format = 'json', $only_show_active_documents = null )
{
  global $db, $only_show_active_documents_default;

  if (null === $only_show_active_documents) {
    $only_show_active_documents = $only_show_active_documents_default;
  }

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('documents');
  if ($only_show_active_documents) {
    $q->andWhere("active = '1'");
  }
  $r = $q->execute()->fetchAll();

  if ( !empty($r) ){
    if ( $return_format === 'json' ){
      return json_encode($r);
    } elseif ( $return_format === 'php' ){
      return serialize($r);
    } elseif ( $return_format === 'csv' ){
      // TODO add the csv output support
      return null;
    } else {
      return $r;
    }
  }

  return FALSE;
}

/**
 * Returns all documents that match the ID(s).
 *
 * @param mixed $id Either a string with a single id, e.g. '2', or an array of ids to fetch. Almost always an integer.
 * @param string $return_format A string to denote how the function should return the results. One of 'php', 'json'. Support of 'csv', 'serialzed' coming soon.
 * @param boolean $only_show_active_documents Determines if the database returns all matching documents or just the documents where active = '1'. Defaults to TRUE.
 *
 * @return mixed The results come out of the database as an array indexed by IDs with an associative array formated as "field_name" => "value". Depending on $return_format the array may be post-processed into a json string, a serialized php string, or a csv string.
 */
function documentsFetch( $id = false, $return_format = 'php', $only_show_active_documents = null )
{
  global $db, $documents_primary_key, $only_show_active_documents_default;
  $id_array = (array) $id;
  if (null == $only_show_active_documents) {
    $only_show_active_documents = $only_show_active_documents_default;
  }
  $return_array = array();
  foreach ($id_array as $this_id) {
    //echo $this_id;
    $q = $db->createQueryBuilder();
    $q->select('*');
    $q->from('documents');
    $q->where( $documents_primary_key .' = :key_value' );
    if ($only_show_active_documents) {
      $q->andWhere("active = '1'");
    }
    $q->setParameters( array(':key_value' => $this_id) );
    $r = $q->execute()->fetchAll();
    if (!empty($r)) {
      $return_array[$this_id] = $r[0];
    } else {
      $return_array[$this_id] = null;
    }
    // TODO test if there are results before using the arrray index, otherwise it throws undefined offset notices.
  }
  if ( $return_format === 'json' ){
    return json_encode($return_array);
  } elseif ( $return_format === 'php' ){
    return $return_array;
  } elseif ( $return_format === 'csv' ){
    // TODO add the csv output support
    return null;
  } else {
    return $return_array;
  }
}

/**
 * Search the documents table.
 *
 * @param string $search_field The field to search.
 * @param mixed $search_value The value to match on. Give me a string or an array of values. If it is an array the function will use the SQL `in` operator.
 * @param string $return_format One of json, php, csv, or native. csv may not work yet. php is a serialized string. native is a php array that hasn't been transformed. Defaults to native.
 * @param boolean $only_show_active_documents Determines if the database returns all matching documents or just the documents where active = '1'. Defaults to TRUE.
 *
 * @return mixed String or php array depending on the $return_format. Returns FALSE on errors.
 */
function documentsSearch( $search_field = FALSE, $search_value = FALSE, $return_format = 'native', $only_show_active_documents = null )
{
  global $db, $documents_fields, $only_show_active_documents_default;
  $result = FALSE;

  if (null === $only_show_active_documents) {
    $only_show_active_documents = $only_show_active_documents_default;
  }

  if (!$search_field or !$search_value){
    trigger_error('Missing params for documentsSearch().');
    return FALSE;
  }

  if ( !in_array($search_field, array_keys($documents_fields)) ){
    trigger_error($search_field . ' not found in the documents table.');
    return FALSE;
  }

  $search_field_q = $db->quoteIdentifier($search_field);

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('documents');
  if ($only_show_active_documents) {
    $q->andWhere("active = '1'");
  }

  if (is_array($search_value)) {
    $q->andWhere($search_field_q . ' in (?)');
    $sql = $q->getSQL();
    $stmt = $db->executeQuery($sql,
                              array($search_value),
                              array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                              );
    $result = $stmt->fetchAll();
  } else {
    $q->andWhere($search_field_q . ' = :search_value');
    $q->setParameters( array(':search_value' => $search_value) );
    $result = $q->execute()->fetchAll();
  }

  if ( !empty($result) ){
    if ( $return_format === 'json' ){
      return json_encode($result);
    } elseif ( $return_format === 'php' ){
      return serialize($result);
    } elseif ( $return_format === 'csv' ){
      // TODO add the csv output support
      return FALSE;
    } else {
      return $result;
    }
  }

  return FALSE;
}

/**
 * Set the documents active field to "0" thus making it an archived file.
 *
 * You must feed in an associative array with some identifiable info; either the document_id, the server_name, or a combination of the name, extension, and file size. All the documents that match the criteria will be set to inactive. UNLESS, you set the $guaranty_one_active_file paramter to TRUE; in which case, the query will set the most recently uploaded file to active. TODO maybe I should make these two things (deactivation and making sure that one file is actie) into two separate functions, but it's handy to have them in the same function for now.
 *
 * @param array $search_array Associative array formatted like array("field_name" => "field_value") that can identify the documents.
 * @param boolean $guaranty_one_active_file Defaults to FALSE. This is a flag that will ensure that the function does not archive all the matching files. If set to TRUE, it will try to leave the most recent file as active. If set to TRUE, and the function can not set a file to active, it will trigger an error and return FALSE.
 *
 * @return boolean Returns TRUE when there are no errors; Returns FALSE on any error.
 */
function documentsDeactivate($search_array = FALSE, $guaranty_one_active_file = FALSE)
{
  global $db, $documents_fields, $documents_primary_key;
  $return_boolean = TRUE;

  $g = print_r($guaranty_one_active_file, TRUE);
  error_log($g);

  if (!$search_array or empty($search_array) or !is_array($search_array)){
    trigger_error('Missing params for documentsDeactivate().');
    return FALSE;
  }

  $q = $db->createQueryBuilder();
  $q->select($documents_primary_key, 'upload_timestamp');
  $q->from('documents');
  $q->where("upload_timestamp <> ''");
  
    // $q->where($search_field_q . ' = :search_value');
    // $q->setParameters( array(':search_value' => $search_value) );
    // $result = $q->execute()->fetchAll();

  // create a where clause for each element in the search array
  foreach( $search_array as $key => $value ){
    if ( !in_array($key, array_keys($documents_fields)) ){
      trigger_error($key .' is not a field in the documents table.');
      return FALSE;
    }
    $key_q = $db->quoteIdentifier($key);
    $q->andWhere($key_q . ' = ' . $q->createPositionalParameter($value));
  }

  // if the call needs a guaranty one active record, then we can leave the most current record as active
  $q->orderBy('datetime(upload_timestamp)', 'ASC');

  $sql = $q->getSQL();

  $result = $q->execute()->fetchAll();

  // TODO loop through each of the document IDs in the result and use documentsUpdate to set the 'active' field to zero.
  $ii = 1;
  $count_results = count($result);
  foreach ( $result as $key => $this_doc ){
    $e = print_r($this_doc, TRUE);
    error_log($e);

    try {
      if ( $guaranty_one_active_file && ($ii === $count_results) ) {
        // set the most recent to active
        $ck = documentsUpdate( $this_doc[$documents_primary_key], 'active', '1' );
        // echo "guaranty is true and is last;";
        // var_dump($ck);
        error_log($ck);

      } else {
        // set all the other files to inactive
        $ck = documentsUpdate( $this_doc[$documents_primary_key], 'active', '0' );
        // echo "guaranty is false or is not last;";
        // var_dump($ck);
      }
    } catch (Exception $e) {
      trigger_error($e);
      $return_boolean = FALSE; 
    }
    $ii++;
  }
  return $return_boolean;
}


// stolen from here: https://github.com/blueimp/jQuery-File-Upload/wiki/PHP-MySQL-database-integration
class ShotsUploadHandler extends UploadHandler 
{

  // in the sample code they initialized a db connection here, but i should already have a $db object in the global space
  protected function initialize() 
  {
    // global $db;
    // this calls the original upload handler initializer
    parent::initialize();
  }

  protected function handle_form_data($file, $index) 
  {
    error_log('ShotsUploadHandler handle_form_data has been called.');

    $stuff = print_r($file, TRUE) . 
             print_r($_REQUEST, TRUE) . 
             print_r($_GET, TRUE) .
             print_r($_POST, TRUE)
             ;
    error_log($stuff);

    // TODO implement the $index param to make this handle multiple files;
    $file->title            = grabString('title');
    $file->description      = grabString('description');
    $file->from_entity_type = grabString('from_entity_type');
    $file->from_entity_id   = grabString('from_entity_id');

  }

  protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) 
  {
    global $db, $app_root, $server_file_storage_root;
    error_log('app root: ' . $app_root);
    error_log('storage root: ' . $server_file_storage_root);

    // set all the default values
    $obfus_name              = null;
    $uploaded_file_timestamp = date("Y-m-d H:i:s");
    $version                 = 1;
    $active                  = 1;
    
    if ( !is_null($name) ){
      $pathinfo = pathinfo($name);
      $pathinfo_string = print_r($pathinfo, TRUE);
      error_log("\npathinfo()\n" .$pathinfo_string);

      $uploaded_file_timestamp = date("Y-m-d H:i:s", filemtime($uploaded_file));
      // $st = print_r($file_upload_time, TRUE);
      // error_log("\nfilemtime()\n". $st);

      // look for exact matching name and extension
      // TODO should this be searching for server_name or name?
      // name can be duplicated; server_name should be unique
      // i should make server_name = md5( name + version ) + extension;
      $q = $db->prepare('select count(*) from documents where name = :name and extension = :extension');
      $q->bindValue('name',      $pathinfo['filename']);
      $q->bindValue('extension', $pathinfo['extension']);
      $q->execute();
      $matches = $q->fetchAll()[0]['count(*)'];

      // guess the version number
      $version = 1;
      if (!empty($matches)) {
        $version = $matches + 1;
      }

      // by default this file will be active
      $active = 1;

      // make up a obfusicated md5 filename
      $obfus_file_part = md5($pathinfo['filename'] . $version);
      $obfus_name = $obfus_file_part . '.' . $pathinfo['extension'];
    } 

    // error_log("\nobfus name:\n". $obfus_name);

    $file = parent::handle_file_upload($uploaded_file, 
                                       $obfus_name, 
                                       $size, 
                                       $type, 
                                       $error, 
                                       $index, 
                                       $content_range
                                       );

    $file_string = print_r($file, TRUE);
    error_log("\nfile object:\n". $file_string);
   
    if (empty($file->error)) {

      $ck = documentsAdd('server_name', $file->name);

      // fourth param of documentsSearch must be set to FALSE to return all docs not just the active docs
      $new_documents = documentsSearch('server_name', $file->name, 'native', FALSE);
      $new_documents_string = print_r($new_documents, TRUE);
      error_log("\n last insert id:\n" .$new_documents_string);

      $new_id = $new_documents[0]['document_id'];

      if (!empty($new_id)) {
        try {
          $file->id = $new_id;

          $server_file_url = $file->url;
          if ( isset($app_root) && isset($server_file_storage_root) ) {
            $server_file_url = '' . $app_root . $server_file_storage_root . '/' . $file->name;
          }

          // *************************************
          // update the metadata for the new file
          // *************************************
          $ck_n = documentsUpdate($new_id, 'name',             $pathinfo['filename']);
          $ck_e = documentsUpdate($new_id, 'extension',        $pathinfo['extension']);
          $ck_s = documentsUpdate($new_id, 'size',             $file->size);
          $ck_m = documentsUpdate($new_id, 'mime_type',        $file->type);
          $ck_u = documentsUpdate($new_id, 'url',              $server_file_url);
          $ck_t = documentsUpdate($new_id, 'title',            $file->title);
          $ck_d = documentsUpdate($new_id, 'description',      $file->description);
          $ck_p = documentsUpdate($new_id, 'upload_timestamp', $uploaded_file_timestamp);
          $ck_v = documentsUpdate($new_id, 'version',          $version);
          $ck_a = documentsUpdate($new_id, 'active',           $active);

          // ******************************************
          // deactive any old versions of the same file
          // ******************************************
          $to_deactivate = array("name" => $pathinfo['filename'],
                                 "extension" => $pathinfo['extension'],
                                 "size" => $file->size
                                 );
          // second param of documentsDeactivate is set to true to make sure that the most recent version of this file is still set to active;
          $ck_deactivate = documentsDeactivate($to_deactivate, TRUE);

          // ******************************************
          // update the relationships table
          // ******************************************
          $from_entity_type = $file->from_entity_type;
          $from_entity_id   = $file->from_entity_id;
          if ( !empty($from_entity_type) && !empty($from_entity_id) ){
            $ck_relate = relationshipsAdd($from_entity_type,
                                          $from_entity_id,
                                          'documents',
                                          $new_id
                                          );
          }
          // $stuff = print_r(get_defined_vars(), TRUE);
          // error_log($stuff);
        } catch (Exception $e) {
          trigger_error($e);
        } // end try-catch for all the metadata db updates
      } // end if new_id is not empty
    } // end if $file->error is empty
    return $file;
  }

  protected function set_additional_file_properties($file) 
  {
      parent::set_additional_file_properties($file);
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // TODO redesign this to use a function like documentsFetch;

          // $sql = 'SELECT `id`, `type`, `title`, `description` FROM `'
          //     .$this->options['db_table'].'` WHERE `name`=?';
          // $query = $this->db->prepare($sql);
          // $query->bind_param('s', $file->name);
          // $query->execute();
          // $query->bind_result(
          //     $id,
          //     $type,
          //     $title,
          //     $description
          // );
          // while ($query->fetch()) {
          //     $file->id = $id;
          //     $file->type = $type;
          //     $file->title = $title;
          //     $file->description = $description;
          // }
      }
  }

  public function delete($print_response = true) {
    // I will not actually delete any files
    // $response = parent::delete(false);

    // TODO use documentsUpdate to set the active field to false;
    // return $this->generate_response($response, $print_response);
    return 'delete failed';
  }
}



// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>
