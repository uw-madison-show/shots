<?php

include_once './lib/all_pages.php';


$sm = $db->getSchemaManager();
$fields = $sm->listTableColumns('grants');
$primary_key = $sm->listTableIndexes('grants')['primary']->getColumns()[0];



//fetchGrants
/**
 * Returns all grants that match the IDs in the array.
 *
 * Feed in an array of IDs to matche the grant_id field and this passes back an
 * array of DBAL Statements
 *
 * @param array $id_array The array of ids to fetch. Can be integer or string (guid).
 *
 * @return array Indexed by IDs with an associative array for each record.
 */
function fetchGrants( $id_array = array() )
{
  global $db, $primary_key;
  $return_array = array();
  foreach ($id_array as $this_id) {
    echo $this_id;
    $q = $db->createQueryBuilder();
    $q->select('*');
    $q->from('grants');
    $q->where( $primary_key .' = :key_value' );
    $q->setParameters( array(':key_value' => $this_id) );
    $r = $q->execute()->fetchAll()[0];
    $return_array[$this_id] = $r;
  }
  return $return_array;
}


//searchGrants

//createGrantFieldHtml
/**
 * Returns the HTML for a field in the grants table.
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
function createGrantFieldHtml( $field_name = FALSE, $field_value = FALSE, $options = array() )
{
  global $db, $fields;
  if ($field_name === FALSE or $field_value === FALSE) return FALSE; 

  // print_r($fields);
  // echo $field_name;

  if ( !in_array($field_name, array_keys($fields)) ){
    // TODO error message 
    return FALSE;
  }

  $field_value = encode($field_value);

  $return_html = '';

  // set up opening divs and spans
  $return_html .= '<div class="field">';

  // e.g. drop down lookups
  $special_fields = array();
  if ( in_array($field_name, $special_fields) ){
    // do stuff for speical fields
  } else {
    // do stuff for normal fields

    $return_html .= '<label for="'. $field_name . '">'. convertFieldName($field_name) .': </label>';
    // figure out if i have integer, string, text, date, etc.
    // based on the DBAL Types
    $field_type = $fields[$field_name]->getType();

    // echo "my field type: ";
    // echo $field_type;
    switch ($field_type) {
      case 'Integer':
      case 'Decimal':
      case 'String':
      case 'Date':
        // add a normal input field
        $return_html .= '<input type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'"/>';
        break;
      case 'Text':
        // add a textarea
        break;
      default:
        // TODO add a default
        break;
    } // end switch
  } // end if field name is in special fields

  // apply options

  // close the divs and spans

  return $return_html;

}

//viewGrant

//addGrants

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
function updateGrant( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $fields;

  if ($id_value === FALSE
      or $field_name === FALSE
      or $new_value === NULL
      ){
    // TODO error message
    echo 'missing params for updateGrant';
    return FALSE;
  }

  $return_bool = FALSE;

  if ( !in_array($field_name, array_keys($fields)) ){
    // TODO error message
    return FALSE;
  }


  // TODO coerce $new_value into the appropriate data type for the column


  $affected_rows = $db->update('grants', 
                               array($field_name => $new_value), 
                               array('grant_id' => $id_value)
                               );

  if ($affected_rows > 0) $return_bool = TRUE;

  return $return_bool;
}

//deleteGrants







// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>
