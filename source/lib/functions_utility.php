<?php
/**
 * Group of functions and shortcuts I like to use.
 *
 * Stolen, adapted, and simplified from many different PHP frameworks.
 * flourishlib: http://flourishlib.com/
 *
 */

/**
 * Converts all special characters to entites, using UTF-8.
 *
 * Stolen from: https://github.com/flourishlib/flourish-classes/blob/master/fHTML.php
 *
 * @param  string|array $content  The content to encode
 * @return string  The encoded content
 *
 */
function encode($content)
{
  if (is_array($content)) {
    return array_map('encode', $content);
  }
  return htmlentities($content, ENT_QUOTES, 'UTF-8');
}


/**
 * Convert a database_field_name into a human readable Database Field Name.
 *
 * @param string $input_string The string to convert (usually a database field name).
 * @return string The converted string.
 *
 */
function convertFieldName( $input_string = FALSE )
{
  return ucwords(preg_replace('/[\/_\-\.\\+@`~\|]/', ' ', $input_string));
}


/**
 * Gets a value from _GET or _POST and converts it to string.
 *
 * Looks in _GET first then in _POST. Uses filter_input() and applies the FILTER_SANITIZE_STRING filter.
 *
 * @param string $variable_name The name of the variable you want.
 * @return string 
 */
function grabString( $variable_name = FALSE )
{
  $value = '';
  if ( !$variable_name ) { return $value; }
  $value = filter_input(INPUT_GET, $variable_name, FILTER_SANITIZE_STRING);
  if ( empty($value) ) {
    $value = filter_input(INPUT_POST, $variable_name, FILTER_SANITIZE_STRING);
  }
  return $value;
}

/** 
 * Makes a new DateTime object, sets the timezone to the app default, and reformats the return value as necessary based on the 2nd and 3rd paramters.
 * 
 * Default timezone is set in the <app_root>/lib/shots/internals/settings_global.php file.
 *
 * @param object|string|integer $date The date to represent. If you feed in NULL or FALSE you get back FALSE.
 * @param string $return_format Optional. Defaults to 'string'. Can be one of 'string', 'DateTime', or 'timestamp'. If 'string' then I assume you want an ISO compliant date or datetime string. If 'timestamp', then you get a Unix timestamp (as a string). The timestamp can be feed into date() to get a formatted date. If 'DateTime' you get back a PHP DateTime object.
 * @param string $return_data Optional. Defaults to 'datetime'. Can be on of 'datetime', 'date', 'time'. If $return_format is 'string' you will get, e.g., datetime = '2016-05-31 13:23:00', date = '2016-05-31', or time = '13:23:00'. If $return_format is 'DateTime', and $return_data is 'date' you will get an object where the time portion of the date is set to '00:00:00.000000'. When $return_format is 'DateTime', 'time' and 'datetime' are ignored. When $return_format is 'timestamp' ... I am not sure what happens.
 *
 * @return mixed Returns a string (ISO formatted or Unix Timestamp), DateTime object, or FALSE on failure.
 */
function handleDateString( $date = FALSE, $return_format = 'string', $return_data = 'datetime' )
{
  global $shots_default_timezone;

  $return_value = FALSE;

  try { 
    if ( is_integer($date) ) {

      // assume this is a unix timestamp and assume it is coming in
      // with the default timezone since unix timestamps do not 
      
      $datetime_object = new DateTime();
      $datetime_object->setTimestamp($date);
      $datetime_object->setTimezone($shots_default_timezone);
      
    } elseif ( is_string($date) ) {
      
      // if the date is coming in with a specified timezone doing the
      // create and setTimezone in two steps will move it to the SHOTs
      // timezone
    
      $datetime_object = new DateTime($date);
      $datetime_object->setTimezone($shots_default_timezone);
    
    } elseif ( is_object($date) ) {

      // try to coerce this object into a string and then do the same
      // datetime thing
    
      $date = $date->__toString();
      $datetime_object = new DateTime($date);
      $datetime_object->setTimezone($shots_default_timezone);
    
    } else {

      // if date came in as an array, boolean, null, etc.;
      // TODO maybe this should not fail silently? but what else can i do?
      return FALSE;

    } // end if else if for the type of incoming data
  } catch (Exception $e) {
    trigger_error('PHP could not parse the data you provided ['. encode($date) . '] as a valid date.');
    return FALSE;
  }
    

  if ( isset($datetime_object) ){

    if ( $return_format === 'string' ) {

      $string_format_to_return = 'Y-m-d H:i:s';

      if ( $return_data === 'date' ) {

        $string_format_to_return = 'Y-m-d';

      } elseif ( $return_data === 'time' ) {

        $string_format_to_return = 'H:i:s';

      }

      $return_value = date_format($datetime_object,
                                  $string_format_to_return
                                  );

    } elseif ( $return_format === 'timestamp' ){

      $return_value = (string) date_timestamp_get($datetime_object);

    } elseif ( $return_format == 'DateTime' ){

      if ( $return_data === 'date' ){
        $datetime_object->setTime(0, 0, 0);
      }

      $return_value = $datetime_object;

    } 
  } // end if datetime object is set

  return $return_value;

}

?>