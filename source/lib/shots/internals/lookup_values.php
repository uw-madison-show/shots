<?php

include_once 'all_pages.php';
include_once 'functions_database.php';

// get schema info
$sm                        = $db->getSchemaManager();
$lookup_values_fields      = $sm->listTableColumns('lookup_values');
$lookup_values_primary_key = $sm->listTableIndexes('lookup_values')['primary']->getColumns()[0];



/**
 * Returns all lookup values that match the ID(s).
 *
 * @param mixed $id Either a string with a single id, e.g. '2', or an array of ids to fetch. Almost always an integer.
 * @param string $return_format A string to denote how the function should return the results. One of 'php', 'json'. Support of 'csv', 'serialzed' coming soon.
 *
 * @return mixed The results come out of the database as an array indexed by IDs with an associative array formated as field_name => value. Depending on $return_format the array may be post-processed into a json string, a serialized php string, or a csv string.
 */
function lookup_valuesFetch( $id = false, $return_format = 'php' )
{
  global $db, $people_primary_key;
  $id_array = (array) $id;
  $return_array = array();
  foreach ($id_array as $this_id) {
    //echo $this_id;
    $q = $db->createQueryBuilder();
    $q->select('*');
    $q->from('lookup_values');
    $q->where( $people_primary_key .' = :key_value' );
    $q->setParameters( array(':key_value' => $this_id) );
    $r = $q->execute()->fetchAll()[0];
    $return_array[$this_id] = $r;
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
 * Returns all lookups in the database.
 *
 * Default return value is a json string.
 *
 * @param string $return_format Default is "json". Can be set to "php" or "csv".
 *
 * @return string Depending on the `$return_format` this is either a json, serialized php, or csv string.
 */
function lookup_valuesFetchAll( $return_format = 'json' )
{
  global $db, $people_primary_key;

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('lookup_values');
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


//searchGrants

/**
 * Returns the HTML for a select element in the lookup values table.
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
function lookup_valuesCreateFieldHtml( $field_name = FALSE, $field_value = FALSE, $options = array() )
{
  global $db, $people_fields;
  if ($field_name === FALSE or $field_value === FALSE) return FALSE; 

  if ( !in_array($field_name, array_keys($people_fields)) ){
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
    $field_type = $people_fields[$field_name]->getType();

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
      case 'Boolean':
        $return_html .= '<div class="col-xs-2">';
        $return_html .= '<input class="form-control" type="checkbox" value="" id="' . $field_name . '" name="' . $field_name . '" ';
        if ($field_value == true) $return_html .= ' checked ';
        $return_html .= '/>';
        $return_html .= '</div>';
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

//Add a lookup value
function lookup_valuesAdd( $field_name = FALSE, $field_value = FALSE )
{
  global $db, $lookup_values_fields;

  if ($field_name === FALSE or $field_value === FALSE) { 
    return FALSE; 
  }

  if ( !in_array($field_name, array_keys($lookup_values_fields)) ) { 
    return FALSE;
  } 

  $check = addRecord('lookup_values', $field_name, $field_value);

  return $check;
}

/**
 * Changes a value in the lookup values table.
 *
 * You provide the key value, the field name, and the new value.
 * 
 * @param integer $id_value The id value for the grant for the update.
 * @param string $field_name Field to be updated.
 * @param mixed $new_value New value. Can be integer, string, etc. depending on the type of $field_name.
 *
 * @return boolean
 */
function lookup_valuesUpdate( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $lookup_values_fields;

  if ($id_value === FALSE
      or $field_name === FALSE
      or $new_value === NULL
      ){
    // TODO error message
    echo 'missing params for lookupUpdate';
    return FALSE;
  }

  $return_bool = FALSE;

  if ( !in_array($field_name, array_keys($lookup_values_fields)) ){
    // TODO error message
    return FALSE;
  }

  // TODO coerce $new_value into the appropriate data type for the column

  // $check = $db->update('people', 
  //                      array($field_name => $new_value), 
  //                      array('person_id' => $id_value)
  //                      );
  $check = updateRecord('lookup_values',
                        array($field_name => $new_value),
                        'lookup_value_id',
                        $id_value
                        );

           

  if ($check > 0) $return_bool = TRUE;

  return $return_bool;
}

//deleteGrants







// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>