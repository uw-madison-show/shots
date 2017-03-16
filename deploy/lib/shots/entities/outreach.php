<?php

include_once 'all_pages.php';
include_once 'functions_database.php';


$sm = $db->getSchemaManager();
$outreach_fields = $sm->listTableColumns('outreach');
$outreach_primary_key = $sm->listTableIndexes('outreach')['primary']->getColumns()[0];



/**
 * Returns all outreach that match the ID(s).
 *
 * @param mixed $id Either a string with a single id, e.g. '2', or an array of ids to fetch. Almost always an integer.
 * @param string $return_format A string to denote how the function should return the results. One of 'php', 'json'. Support of 'csv', 'serialzed' coming soon.
 *
 * @return mixed The results come out of the database as an array indexed by IDs with an associative array formated as field_name => value. Depending on $return_format the array may be post-processed into a json string, a serialized php string, or a csv string.
 */
function outreachFetch( $id = false, $return_format = 'php' )
{
  global $db, $outreach_primary_key;
  $id_array = (array) $id;
  $return_array = array();
  foreach ($id_array as $this_id) {
    //echo $this_id;
    $q = $db->createQueryBuilder();
    $q->select('*');
    $q->from('outreach');
    $q->where( $outreach_primary_key .' = :key_value' );
    $q->setParameters( array(':key_value' => $this_id) );
    $r = $q->execute()->fetchAll();
    $result = array();
    if ( !empty($r) ){
      $result = $r[0];
    }
    $return_array[$this_id] = $result;
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
 * Returns all outreach records in the database.
 *
 * Default return value is a json string.
 *
 * @param string $return_format Default is "json". Can be set to "php" or "csv".
 *
 * @return string Depending on the `$return_format` this is either a json, serialized php, or csv string.
 */
function outreachFetchAll( $return_format = 'json' )
{
  global $db, $outreach_primary_key;

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('outreach');
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
 * Get the most recently editted outreach record.
 *
 * In the future this function might be extended based on the username.
 *
 * @param integer $count The number of outreach to return.
 * @param string $return_format Defaults to 'php'.
 *
 * @return mixed Depending on $return_format returns data on outreach in a string/array.
 */
function outreachFetchRecent( $count = 3, $return_format = 'php')
{
  global $db, $outreach_primary_key;

  $q = $db->createQueryBuilder();
  $q->select('key_value');
  $q->from('changelog');
  $q->where('key_field = :key_field');
  $q->groupBy('key_value');
  $q->orderBy('change_timestamp', 'DESC');
  $q->setMaxResults( $count );

  $q->setParameters( array(':key_field' => $outreach_primary_key) );

  $r = $q->execute()->fetchAll();

  if ( !empty($r) ){
    $f = array_column($r, 'key_value');
    if ( !empty($f) ){
      return outreachFetch($f, $return_format);
    }
  }

  return FALSE;

}


// TODO searchoutreach

/**
 * Returns the HTML for a field in the outreach table.
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
function outreachCreateFieldHtml( $field_name = FALSE, $field_value = FALSE, $options = array() )
{
  global $db, $outreach_fields, $outreach_primary_key;
  if ($field_name === FALSE or $field_value === FALSE) return FALSE; 

  // print_r($outreach_fields);
  // echo $field_name;

  if ( !in_array($field_name, array_keys($outreach_fields)) ){
    trigger_error($field_name . ' is not a recognized field in the outreach table.');
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
  $special_fields = array();
  if ( in_array($field_name, $special_fields) ){
    
  } else {
    // do stuff for normal fields

    // figure out if i have integer, string, text, date, etc.
    // based on the DBAL Types
    $field_type = $outreach_fields[$field_name]->getType();

    // echo "my field type: ";
    // echo $field_type;
    switch ($field_type) {
      case 'Integer':
      case 'Decimal':
      case 'String':
      case 'Date':
        // add a normal input field for all four types
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<input class="form-control" type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'"';
        if ($field_name === $outreach_primary_key) {
          $return_html .= ' readonly';
        }
        $return_html .= ' />';
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


/**
 * Adds a record to the outreach table.
 *
 * @param string $field_name The database field that will be used for the insert. This field should probably not be the autoincrement primary key.
 * @param string $field_value Value to be inserted into $field_name.
 *
 * @return mixed Should be the key of the newly created record or FALSE on error.
 */
function outreachAdd( $field_name = FALSE, $field_value = FALSE )
{
  global $db, $outreach_fields;

  if ($field_name === FALSE or $field_value === FALSE) { return FALSE; }

  if ( !in_array($field_name, array_keys($outreach_fields)) ) { return FALSE;  } 

  return addRecord('outreach',
                   $field_name,
                   $field_value
                   );

}

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
function outreachUpdate( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $outreach_fields, $outreach_primary_key;

  if ($id_value === FALSE
      or $field_name === FALSE
      or $new_value === NULL
      ){
    trigger_error('Missing params for outreachUpdate().');
    return FALSE;
  }

  if ( !in_array($field_name, array_keys($outreach_fields)) ){
    trigger_error($field_name .' not in outreach table.');
    return FALSE;
  }

  return updateRecord('outreach',
                      array($field_name => $new_value),
                      $outreach_primary_key,
                      $id_value
                      );
}

/**
 * Deletes record from the outreach table.
 *
 * @param string $id_value The id of the record you want to delete.
 * 
 * @return boolean Returns true on success and false on failure.
 */
function outreachDelete($id_value)
{
  global $db, $outreach_primary_key;
  return deleteRecord('outreach',
                      $outreach_primary_key,
                      $id_value
                      );
}


/**
 * Search the outreach table.
 *
 * @param string $search_field The field to search.
 * @param mixed $search_value The value to match on. Give me a string or an array of values. If it is an array the function will use the SQL `in` operator.
 * @param string $return_format One of json, php, csv, or native. csv may not work yet. php is a serialized string. native is a php array that hasn't been transformed. Defaults to native.
 *
 * @return mixed String or php array depending on the $return_format. Returns FALSE on errors.
 */
function outreachSearch( $search_field = FALSE, $search_value = FALSE, $return_format = 'native' )
{
  global $db, $outreach_fields;
  $result = FALSE;

  if (!$search_field or !$search_value){
    trigger_error('Missing params for outreachSearch().');
    return FALSE;
  }

  if ( !in_array($search_field, array_keys($outreach_fields)) ){
    trigger_error($search_field . ' not found in the documents table.');
    return FALSE;
  }

  $search_field_q = $db->quoteIdentifier($search_field);

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('outreach');
  
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




// echo '<pre>';
// print_r(get_defined_vars());
// echo '</pre>';
?>
