<?php

require_once('all_pages.php');
require_once('jQuery-File-Upload/UploadHandler.php');
include_once 'functions_database.php';


$sm                    = $db->getSchemaManager();
$documents_fields      = $sm->listTableColumns('documents');
$documents_primary_key = $sm->listTableIndexes('documents')['primary']->getColumns()[0];


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

  // print_r($grants_fields);
  // echo $field_name;

  if ( !in_array($field_name, array_keys($grants_fields)) ){
    // TODO error message 
    return FALSE;
  }

  $field_value = encode($field_value);

  $return_html = '';

  // set up opening divs and spans
  $return_html .= '<div class="field">';
  $return_html .= '<div class="form-group">';

  // e.g. drop down lookups
  $special_fields = array();
  if ( in_array($field_name, $special_fields) ){
    // do stuff for speical fields
  } else {
    // do stuff for normal fields

    // set up the label
    $return_html .= '<label class="control-label col-xs-4" for="'. $field_name . '">'. convertFieldName($field_name) .'</label>';
    // figure out if i have integer, string, text, date, etc.
    // based on the DBAL Types
    $field_type = $grants_fields[$field_name]->getType();

    // echo "my field type: ";
    // echo $field_type;
    switch ($field_type) {
      case 'Integer':
      case 'Decimal':
      case 'String':
      case 'Date':
        // add a normal input field for all four types
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<input class="form-control" type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'"/>';
        $return_html .= '</div>';
        break;
        // TODO make date fields a date picker input
      case 'Text':
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<textarea class="form-control" rows="2" id="'. $field_name . '" name="'. $field_name . '">' . $field_value . '</textarea>';
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

class ShotsUploadHandler extends UploadHandler 
{

  // 
  protected function initialize() 
  {
    global $db;
    $this->db = $db;
    // this calls the original upload handler initializer
    parent::initialize();
    //$this->db->close();
  }

  protected function handle_form_data($file, $index) 
  {
    $file->title = @$_REQUEST['title'][$index];
    $file->description = @$_REQUEST['description'][$index];
  }

  protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) 
  {
    $file = parent::handle_file_upload($uploaded_file, 
                                       $name, 
                                       $size, 
                                       $type, 
                                       $error, 
                                       $index, 
                                       $content_range
                                       );
    if (empty($file->error)) {
      $ck = documentsAdd('title', '');
      $new_id = $this->db->lastInsertID();
      try {
        $ck_n = documentsUpdate($new_id, 'name', $file->name);
        $ck_t = documentsUpdate($new_id, 'title', $file->title);
        $ck_s = documentsUpdate($new_id, 'size',  $file->size);
        // TODO add all the other fields for this document...
        // TODO figure out if this document is a newer version of an existing document
      } catch (Exception $e) {
        trigger_error($e);
      }
    
      // $sql = 'INSERT INTO `'.$this->options['db_table']
      //     .'` (`name`, `size`, `type`, `title`, `description`)'
      //     .' VALUES (?, ?, ?, ?, ?)';
      // $query = $this->db->prepare($sql);
      // $query->bind_param(
      //     'sisss',
      //     $file->name,
      //     $file->size,
      //     $file->type,
      //     $file->title,
      //     $file->description
      // );
      // $query->execute();
      $file->id = $new_id;
    }
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

        return $this->generate_response($response, $print_response);
    }
}

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
 * @return boolean
 */
function documentsUpdate( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $documents_fields, $documents_primary_key;

  if ($id_value === FALSE
      or $field_name === FALSE
      or $new_value === NULL
      ){
    trigger_error('Missing params for documentsUpdate().');
    return FALSE;
  }

  if ( !in_array($field_name, array_keys($grants_fields)) ){
    trigger_error($field_name .' not in documents table.');
    return FALSE;
  }

  return updateRecord('documents',
                      array($field_name => $new_value),
                      $grants_primary_key,
                      $id_value
                      );
}


function documentsFetchAll( $return_format = 'json' )
{
  global $db;

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('documents');
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





// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>
