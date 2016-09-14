<?php

include_once 'all_pages.php';
include_once 'functions_database.php';


$sm = $db->getSchemaManager();
$grants_fields = $sm->listTableColumns('grants');
$grants_primary_key = $sm->listTableIndexes('grants')['primary']->getColumns()[0];



/**
 * Returns all grants that match the ID(s).
 *
 * @param mixed $id Either a string with a single id, e.g. '2', or an array of ids to fetch. Almost always an integer.
 * @param string $return_format A string to denote how the function should return the results. One of 'php', 'json'. Support of 'csv', 'serialzed' coming soon.
 *
 * @return mixed The results come out of the database as an array indexed by IDs with an associative array formated as field_name => value. Depending on $return_format the array may be post-processed into a json string, a serialized php string, or a csv string.
 */
function grantsFetch( $id = false, $return_format = 'php' )
{
  global $db, $grants_primary_key;
  $id_array = (array) $id;
  $return_array = array();
  foreach ($id_array as $this_id) {
    //echo $this_id;
    $q = $db->createQueryBuilder();
    $q->select('*');
    $q->from('grants');
    $q->where( $grants_primary_key .' = :key_value' );
    $q->setParameters( array(':key_value' => $this_id) );
    $r = $q->execute()->fetchAll()[0];
    // TODO test if there are results before using the arrray index, otherwise it throws undefined offset notices.
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

//fetchAllGrants
/**
 * Returns all grants in the database.
 *
 * Default return value is a json string.
 *
 * @param string $return_format Default is "json". Can be set to "php" or "csv".
 *
 * @return string Depending on the `$return_format` this is either a json, serialized php, or csv string.
 */
function grantsFetchAll( $return_format = 'json' )
{
  global $db, $grants_primary_key;

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('grants');
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
function grantsCreateFieldHtml( $field_name = FALSE, $field_value = FALSE, $options = array() )
{
  global $db, $grants_fields;
  if ($field_name === FALSE or $field_value === FALSE) return FALSE; 

  // print_r($grants_fields);
  // echo $field_name;

  if ( !in_array($field_name, array_keys($grants_fields)) ){
    trigger_error($field_name . ' is not a recognized field in the grants table.');
    return FALSE;
  }

  $field_value = encode($field_value);

  $return_html = '';

  // set up opening divs and spans
  $return_html .= '<div class="field">';
  $return_html .= '<div class="form-group">';

  // set up the label
  $return_html .= '<label class="control-label col-xs-4" for="'. $field_name . '">'. convertFieldName($field_name) .'</label>';

  // handle special fields, e.g. drop down lookups
  $special_fields = array('status');
  if ( in_array($field_name, $special_fields) ){
    switch ($field_name) {
      case 'status':

        $lookups = getLookups('grants', 'status');

        // set up the select element
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<select class="form-control " id="' . $field_name . '" name="'. $field_name .'">';

        // deal with the possibility that the db has a value that is not on the lookup list
        if ( !empty($field_value) && array_search($field_value, array_column($lookups, 'lookup_value')) === FALSE ){
          $return_html .= '<option class="drop-down-option-default" value="" selected disabled>'. $field_value .' [invalid option]</option>';
        } else {
          $return_html .= '<option class="drop-down-option-default" value=""></option>';
        }
        foreach ($lookups as $key => $lookup) {
          $return_html .= '<option class="drop-down-option" value="'. $lookup['lookup_value'] .'" ';
          if ( $lookup['lookup_value'] === $field_value ) {
            $return_html .= 'selected';
          }
          $return_html .= '>'. $lookup['label'] . '</option>';
        }
        $return_html .= '</select>';
        $return_html .= '</div>';
        break;
    }
  } else {
    // do stuff for normal fields

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

//addGrant
function grantsAdd( $field_name = FALSE, $field_value = FALSE )
{
 global $db, $grants_fields;
 
 if ($field_name === FALSE or $field_value === FALSE) { return FALSE; }

 if ( !in_array($field_name, array_keys($grants_fields)) ) { return FALSE;  } 

 return addRecord('grants',
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
function grantsUpdate( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $grants_fields, $grants_primary_key;

  if ($id_value === FALSE
      or $field_name === FALSE
      or $new_value === NULL
      ){
    trigger_error('Missing params for grantsUpdate().');
    return FALSE;
  }

  if ( !in_array($field_name, array_keys($grants_fields)) ){
    trigger_error($field_name .' not in grants table.');
    return FALSE;
  }

  return updateRecord('grants',
                      array($field_name => $new_value),
                      $grants_primary_key,
                      $id_value
                      );
}

//delete Grants
function grantsDelete($id_value)
{
  global $db, $grants_primary_key;
  return deleteRecord('grants',
                      $grants_primary_key,
                      $id_value
                      );
}







// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>
