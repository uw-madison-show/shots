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

?>