<?php

include_once 'all_pages.php';
include_once 'functions_database.php';


$sm = $db->getSchemaManager();
$events_fields = $sm->listTableColumns('events');
$events_primary_key = $sm->listTableIndexes('events')['primary']->getColumns()[0];

// sqlite does not have native date, time, or datetime field types so store a list of the date and datetime fields in this array; they will get special processing in the handleDateString() function
$events_date_fields = array('date_start',
                            'date_end',
                            );
$events_time_fields = array('time_start',
                            'time_end',
                            );

/**
 * Returns all events that match the ID(s).
 *
 * @param mixed $id Either a string with a single id, e.g. '2', or an array of ids to fetch. Almost always an integer.
 * @param string $return_format A string to denote how the function should return the results. One of 'php', 'json'. Support of 'csv', 'serialzed' coming soon.
 *
 * @return mixed The results come out of the database as an array indexed by IDs with an associative array formated as field_name => value. Depending on $return_format the array may be post-processed into a json string, a serialized php string, or a csv string.
 */
function eventsFetch( $id = false, $return_format = 'php' )
{
  global $db, $events_primary_key;
  $id_array = (array) $id;
  $return_array = array();
  foreach ($id_array as $this_id) {
    //echo $this_id;
    $q = $db->createQueryBuilder();
    $q->select('*');
    $q->from('events');
    $q->where( $events_primary_key .' = :key_value' );
    $q->setParameters( array(':key_value' => $this_id) );
    $r = $q->execute()->fetchAll();
    // TODO test if there are results before using the arrray index, otherwise it throws undefined offset notices.
    if ( !empty($r[0]) ){
      $return_array[$this_id] = $r[0];
    }
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
 * Returns all events within date range.
 *
 * For now this is using a SQLite function date() to convert strings into datetime values for the comparison. Need to in the future abstract the query so that it can run on any db.
 *
 * @param string $start_date A string that SQLite can format into a date value.
 * @param string $end_date A string that SQLite can format ito a date value.
 *
 * @return array Holding all of the events in the specified range. json_encode() will turn it into a JSON formatted string which can be passed to FullCalendar.
 */
function eventsFetchDateRange($start_date = FALSE, $end_date = FALSE)
{
  global $db;
  $return_array = array();

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('events');
  // TODO make these not be sqlite specific datetime functions; how?
  $q->where('date(date_start) >= date(:start_date)');
  $q->andWhere('date(date_start) <= date(:end_date)');

  $q->setParameter(':start_date', $start_date);
  $q->setParameter(':end_date',   $end_date);

  $r = $q->execute()->fetchAll();

  // TODO convert the event records into FullCalendar json object format?

  $return_array = $r;
  return $return_array;
}

/**
 * Returns the HTML for a field in the events table.
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
function eventsCreateFieldHtml( $field_name = FALSE, $field_value = FALSE, $options = array() )
{
  global $db, $events_fields, $events_primary_key;
  if ($field_name === FALSE or $field_value === FALSE) {
    trigger_error('Field name and field value are required for *CreateFieldHtml functions.');
    return FALSE; 
  }


  if ( !in_array($field_name, array_keys($events_fields)) ){
    trigger_error($field_name . ' is not a valid field in the events table.');
    return FALSE;
  }

  $field_value = encode($field_value);

  $return_html = '';

  // set up opening divs and spans
  $return_html .= '<div class="field">';
  $return_html .= '<div class="form-group">';

  // set up the label
    $return_html .= '<label class="control-label col-xs-4" for="'. $field_name . '">'. convertFieldName($field_name) .'</label>';

  // e.g. drop down lookups
  $special_fields = array('type',
                          'date_start',
                          'date_end',
                          'time_start',
                          'time_end'
                          );
  if ( in_array($field_name, $special_fields) ){
    switch ($field_name) {
      case 'type':

        $lookups = getLookups('events', 'type');

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

      case 'date_end':
      case 'date_start':
        // TODO make date fields a date picker input
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<div class="input-group date datepicker">';
        $return_html .= '<input class="form-control" type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'" />';
        $return_html .= '<span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                         </span>
                         </div>
                         </div>
                         ';
        break;

      case 'time_end':
      case 'time_start':
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<div class="input-group date timepicker">';
        $return_html .= '<input class="form-control" type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'" />';
        $return_html .= '<span class="input-group-addon">
                           <span class="glyphicon glyphicon-time"></span>
                         </span>
                         </div>
                         </div>
                         ';
        break;
    }
  } else {
    // do stuff for normal fields

    // figure out if i have integer, string, text, date, etc.
    // based on the DBAL Types
    $field_type = $events_fields[$field_name]->getType();

    // echo "<br>my field type: ";
    // echo $field_type;
    switch ($field_type) {
      case 'Integer':
      case 'Decimal':
      case 'String':
      case 'Date':
        // add a normal input field for all four types
        $return_html .= '<div class="col-xs-8">';
        $return_html .= '<input class="form-control" type="text" id="' . $field_name . '" name="'. $field_name .'" value="'. $field_value .'"';
        if ($field_name === $events_primary_key) {
          $return_html .= ' readonly';
        }
        $return_html .= '/>';
        $return_html .= '</div>';
        break;
        
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
 * Validate the event start and end fields.
 *
 * @param integer $id_value The id value for the event to validate.
 * @param string $field_name Optional. If you are using eventsValidate from within the eventsUpdate() function, you would pass in the field that is getting updated.
 * @param string $new_value Optional. If you are using eventsValidate from within the eventsUpdate() function, you would pass in the value that is getting updated.
 *
 * @return boolean|string Returns TRUE when the event has valid fields. Returns a string with the warning message when there is invalid fields. Returns FALSE when there is unexpected errors.
 */
function eventsValidate( int $id_value, $field_name = FALSE, $new_value = FALSE )
{
  global $db, $events_fields, $events_primary_key, $events_date_fields, $events_time_fields;

  if ( $id_value === FALSE ){
    trigger_error('Missing params for eventsValidate().');
    return FALSE;
  }

  // de-ref the return array to make the code a little clearner
  $this_event = eventsFetch($id_value)[$id_value];

  if ( !$this_event ){
    trigger_error('Could not find event with id: '. encode($id_value));
    return FALSE;
  }

  if ( $field_name && $new_value &&
       (in_array($field_name, $events_date_fields) or
        in_array($field_name, $events_time_fields)
        )
      ) {
    // we are validating an event with new data
    // replace the existing value with the new value
    $this_event[$field_name] = $new_value;
  } else {
    // we do not have any new data so we are validating the existing data
    // do nothing?
  }

  // things to validate:

  // date_start is not null
  if ( empty($this_event['date_start']) ) {
    trigger_error('Start date can not be empty.');
    return FALSE;
  }

  // if all_day is checked then time_start and time_end should be null
  if ( $this_event['all_day'] === 1 ) {

    // if ( $this_event['time_start'] ) {}

    // if ( $this_event['time_end'] ) {}

  }

  // if date_end is not null, then date_end should be equal to or after date_start
  if ( $this_event['date_end'] ) {
    if ( strtotime($this_event['date_start']) > strtotime($this_event['date_end']) ) {
      trigger_error('End date must be after start date.');
      return FALSE;
    }
  }

  // if time_end is not null, then date_end should be not null

  // if date_end, time_start, and time_end are all not null, then the computed datetime of the end should be after the computed datetime for the start

  return TRUE;
}

/**
 * Changes a value in the events table.
 *
 * You provide the key value, the field name, and the new value.
 * 
 * @param integer $id_value The id value for the event for the update.
 * @param string $field_name Field to be updated.
 * @param mixed $new_value New value. Can be integer, string, etc. depending on the type of $field_name.
 *
 * @return boolean
 */
function eventsUpdate( $id_value = FALSE, $field_name = FALSE, $new_value = NULL )
{
  global $db, $events_fields, $events_primary_key, $events_date_fields, $events_time_fields;

  if ($id_value === FALSE
      or $field_name === FALSE
      or $new_value === NULL
      ){
    trigger_error('Missing params for eventsUpdate().');
    return FALSE;
  }

  if ( !in_array($field_name, array_keys($events_fields)) ){
    trigger_error($field_name .' not in events table.');
    return FALSE;
  }

  $to_be_validated = FALSE;

  if ( in_array($field_name, $events_date_fields) ){
    $to_be_validated = TRUE;

    $temp_date_string = handleDateString($new_value,
                                         'string',
                                         'date'
                                         );
    // var_dump($temp_date_string);
    if ( $temp_date_string === FALSE ){
      trigger_error('Unable to convert the user input into a valid date string.');
      return FALSE;
    } else {
      $new_value = $temp_date_string;
    }
  }

  if ( in_array($field_name, $events_time_fields) ){
    $to_be_validated = TRUE;

    $temp_time_string = handleDateString($new_value,
                                         'string',
                                         'time'
                                         );
    // var_dump($temp_date_string);
    if ( $temp_time_string === FALSE ){
      trigger_error('Unable to convert the user input into a valid time string.');
      return FALSE;
    } else {
      $new_value = $temp_time_string;
    }
  }

  // TODO make sure start date/time, end date/time, and all day fields make sense
  if ( $to_be_validated ){
    $is_valid = eventsValidate($id_value, $field_name, $new_value);
  }

  if ( $to_be_validated && ($is_valid !== TRUE) ){
    return $is_valid;
  } else {
    return updateRecord('events',
                        array($field_name => $new_value),
                        $events_primary_key,
                        $id_value
                        );
  }
}

//delete Event
function eventsDelete($id_value)
{
  global $db, $events_primary_key;
  return deleteRecord('evnts',
                      $events_primary_key,
                      $id_value
                      );
}

/**
 * Add a record to the events table.
 *
 * @param mixed $field Either an array of field => value pairs or a string with the field name. When a string, the name of the field to use in the insert query. Do not use the field name of an autoincrement id field, e.g. do not use event_id.
 * @param mixed $field_value The value to use in the insert query.
 *
 * @return boolean If count of affected rows is greater than 0 then return TRUE, otherwise return FALSE.
 */
function eventsAdd( $field = FALSE, $field_value = FALSE )
{
  global $db, $events_fields, $events_date_fields, $events_time_fields;

  $affected_rows = 0;

  if ( $field === FALSE ) {
    return FALSE;
  } elseif ( is_array($field) ) {
    
    $affected_rows = $db->insert('events',
                                 $field
                                 );

  } elseif ( is_string($field) ) {

    if ( $field_value === FALSE ) { 
      return FALSE; 
    }

    if ( !in_array($field, array_keys($events_fields)) ) { 
      return FALSE;
    } 

    if ( in_array($field, $events_date_fields) ){
      $temp_date_string = handleDateString($field_value,
                                           'string',
                                           'date'
                                           );
      if ( $temp_date_string === FALSE ){
        trigger_error('Event not added because could not convert user input into a valid datetime string.');
        return FALSE;
      } else {
        $field_value = $temp_date_string;
      }
    }

    if ( in_array($field, $events_time_fields) ){
      $temp_time_string = handleDateString($field_value,
                                           'string',
                                           'time'
                                           );
      if ( $temp_time_string === FALSE ){
        trigger_error('Event not added because could not convert user input into a valid datetime string.');
        return FALSE;
      } else {
        $field_value = $temp_time_string;
      }
    }

    $affected_rows = $db->insert('events',
                                array($field => $field_value)
                                );
  }

  

  if ( $affected_rows > 0 ) { return TRUE; }

  return FALSE;
}



/**
 * Returns all events in the database.
 *
 * Default return value is a json string.
 *
 * @param string $return_format Default is "json". Can be set to "php" or "csv".
 *
 * @return string Depending on the `$return_format` this is either a json, serialized php, or csv string.
 */
function eventsFetchAll( $return_format = 'json' )
{
  global $db, $events_primary_key;

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('events');
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
 * Search the events table.
 *
 * @param string $search_field The field to search.
 * @param mixed $search_value The value to match on. Give me a string or an array of values. If it is an array the function will use the SQL `in` operator.
 * @param string $return_format One of json, php, csv, or native. csv may not work yet. php is a serialized string. native is a php array that hasn't been transformed. Defaults to native.
 *
 * @return mixed String or php array depending on the $return_format. Returns FALSE on errors.
 */
function eventsSearch( $search_field = FALSE, $search_value = FALSE, $return_format = 'native' )
{
  global $db, $events_fields;
  $result = FALSE;

  if (!$search_field or !$events_fields){
    trigger_error('Missing params for eventsSearch().');
    return FALSE;
  }

  if ( !in_array($search_field, array_keys($events_fields)) ){
    trigger_error($search_field . ' not found in the documents table.');
    return FALSE;
  }

  $search_field_q = $db->quoteIdentifier($search_field);

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('events');
  
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

