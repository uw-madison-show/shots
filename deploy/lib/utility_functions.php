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
    return array_map(array('encode'), $content);
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

?>