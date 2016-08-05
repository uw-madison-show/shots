<?php

// customized functions for the database abstractions
// for the real CRUD functions see '/lib/shots/entities/<entity>.php'


/**
 * Does the sqlite database exist?
 *
 * We need to test if the database exists but listDatabases() does not work on sqlite, so we'll use this try-catch block to kludge it.
 *
 * @param object $schema_manager Pass in the schema manager instance.
 * @return boolean
 */
function sqliteDatabaseExists( $schema_manager = NULL )
{
  try {
    $tables = $schema_manager->listTables();
    if ($tables) {
      return TRUE;
    }
  } catch (Exception $e) {
    // do nothing
    return FALSE;
  }
  return FALSE;
}

/**
 * A custom error handler for php, database, and DBAL code.
 *
 * The goal is to grab all exceptions, errors, warnings, and notices and write a nice looking message that is hidden in the footer of the app pages. All parameters are defined by PHP for errors. The custom_exception_handler() just reformats the exception to look like an error and passes it to this function. Uncaught exceptions will also get passed in to here, but if you want the script to keep executing you will still need to catch exceptions and explicietly call custom_exception_handler(). Borrowing heavily from SO:
 * http://stackoverflow.com/questions/29522915/error-handling-in-doctrine-dbal-with-comparison-to-php-pdo
 *
 * @param integer $error_code PHP defined error codes that correspond to predefined constants (http://php.net/manual/en/errorfunc.constants.php).
 * @param string $message Message given by the trigger.
 * @param string $file File name that triggered the error.
 * @param string $line Line number in $file that triggered the error.
 * @param array $context Associative array of every variable, and their values, in use when the error occured.
 */
function custom_error_handler($error_code, $message, $file, $line, $context)
{

  $error_code_map = array(E_WARNING           => 'Warning',
                          E_NOTICE            => 'Notice',
                          E_USER_ERROR        => 'Custom Error Trigger',
                          E_USER_WARNING      => 'Cusome Warning Trigger',
                          E_USER_NOTICE       => 'Custom Notice Trigger',
                          E_STRICT            => 'Strict PHP Notice',
                          E_DEPRECATED        => 'Deprecated PHP Notice',
                          E_USER_DEPRECATED   => 'Custom Deprecated PHP Notice',
                          'EXCEPTION'         => 'Exception'
                          );
  $html_output = '';

  $error_label = isset( $error_code_map[$error_code] ) ? $error_code_map[$error_code] : 'Unknown Error Type (code: '. $error_code . ')';

  $output_message = isset( $message ) ? $message : '';

  $output_file = isset( $file ) ? 'File: ' . $file : '(unspecified file)';
  $output_line = isset( $line ) ? 'Line: ' . $line : '(unspecified line)';

  $stacktrace = '';
  if ( $error_code === 'EXCEPTION' && isset($context) ){
    // create a readable stacktrace from the default exception getTrace()
    $stacktrace .= 'Stacktrace from the exception:' . PHP_EOL;
    foreach( $context as $step => $details ){
      $stacktrace .= PHP_EOL . '#' . $step . ' ';
      foreach ($details as $key => $value) {
        switch($key) {
          case 'file':
            $stacktrace .= $value;
            break;
          case 'line':
            $stacktrace .= ' (line: '. $value . ')';
            break;
          case 'function':
          case 'class':
          case 'type':
            $stacktrace .= ' '. $key .': '. $value;
            break;
          case 'args':
            foreach ($value as $arg_name => $arg_value) {
              $arg_name = is_object($arg_name) ? 'object' : is_array($arg_name) ? 'array' : trim(preg_replace('/\s+/', ' ', $arg_name));
              $arg_value = is_object($arg_value) ? 'object' : is_array($arg_value) ? 'array' : trim(preg_replace('/\s+/', ' ', $arg_value));
              if ( !empty($arg_name) or !empty($arg_value) ){
                $stacktrace .= PHP_EOL . '     params: ' . $arg_name . ' = ' . $arg_value;
              }
            }
            break;
        }
      }
    }
  } elseif ( $error_code != 'EXCEPTION' && isset($context) ) {
    $stacktrace .= 'Context for the error:'. PHP_EOL;
    foreach ($context as $key => $value) {
      $key = is_object($key) ? 'object' : is_array($key) ? 'array' : trim(preg_replace('/\s+/', ' ', $key));
      $value = is_object($value) ? 'object' : is_array($value) ? 'array' : trim(preg_replace('/\s+/', ' ', $value));
      if ( !empty($key) or !empty($value) ){
        $stacktrace .= PHP_EOL . 'Variable: ' . $key . ' = ' . $value;
      }
    }
  }

  $random_number = rand(100, 1000);

  echo '<div class="panel-group  server-side-error-message">';
  echo '<div class="panel panel-default">';
  echo '<div class="panel-heading">';
  echo '<h4 class="panel-title"><a data-toggle="collapse" href="#e'. $random_number .'">' . $error_label . '...</a></h4>';
  echo '</div>';
  echo '<div id="e'. $random_number .'" class="panel-collapse collapse">';
  echo '<div class="panel-body">';
  echo '<p>' . $output_message . '</p>';
  echo '<p>' . $output_file . '</p>';
  echo '<p>' . $output_line . '</p>';
  echo '<pre>'. $stacktrace . '</p>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
  echo '</div>';

  return TRUE;
}

function custom_exception_handler( $exception_object )
{

  $error_code = 'EXCEPTION';
  $message    = $exception_object->getMessage() ? $exception_object->getMessage() : '';
  $file       = $exception_object->getFile() ? $exception_object->getFile() : '';
  $line       = $exception_object->getLine() ? $exception_object->getLine() : '';
  $context    = $exception_object->getTrace() ? $exception_object->getTrace() : array();

  if ( $exception_object->getCode() ){
    $message .= ' (Exception code: ' . $exception_object->getCode() . ')';
  }

  if ( $error_code && $message ) {
    custom_error_handler($error_code,
                         $message,
                         $file,
                         $line,
                         $context
                         );
  } else {
    // I don't know you gave me an ill-formatted exception.
  }

  return TRUE;
}

set_exception_handler('custom_exception_handler');
set_error_handler('custom_error_handler');


function changelog( $table_name, $table_key_field, $key_value, $field_name, $old_value, $new_value )
{
  global $db;
  $db->insert('changelog',
              array('table_name' => strtolower($table_name),
                    'key_field' => strtolower($table_key_field),
                    'key_value' => strtolower($key_value),
                    'field'     => strtolower($field_name),
                    'old_value' => $old_value,
                    'new_value' => $new_value
                    // TODO add username and datetime to the changelog record
                    )
              );
}

/**
 * Make an edit to one row in the database.
 *
 * @param string $table_name Name of the database table.
 * @param array  $edits An array of name => value pairs for the fields to be updated. E.g. array("title" => "new title", "status" => "complete").
 * @param string $key_field Name of the primary key in the table.
 * @param string $id Value of the key where the update will get made.
 *
 * @return mixed Returns TRUE if there are no errors and at least one update. Returns FALSE if there are no errors, but there were no updates. Returns an error message if there are db/php errors.
 */
function updateRecord($table_name = NULL, $edits = array(), $key_field = NULL, $id = NULL)
{
  global $db;
  $return_boolean = FALSE;

  // $table_name_q = $db->quoteIdentifier($table_name);
  $key_field_q = $db->quoteIdentifier($key_field);
  foreach ($edits as $field => $value) {
    // find old value
    // $field_q = $db->quoteIdentifier($field);
    $query = $db->createQueryBuilder();
    $query->select($field)
          ->from($table_name)
          ->where($key_field_q . ' = ?')
          ->setParameter(0, $id)
          ;
    $statement = $query->execute();
    $old_value = $statement->fetchColumn(0);

    // make the update
    if ( $value != $old_value ){
      $affected_rows = $db->update($table_name,
                                   array($field => $value),
                                   array($key_field => $id)
                                   );
      if ( $affected_rows > 0 ){
        // if the update is successful, add record to changelog
        $c = changelog($table_name,
                       $key_field,
                       $id,
                       $field,
                       $old_value,
                       $value
                       );
        $return_boolean = TRUE;
      }
    }
  }
  return $return_boolean;
}

function addRecord($table_name = NULL, $field_name = NULL, $field_value = NULL)
{
  global $db;

  $sm = $db->getSchemaManager();
  $key_field = $sm->listTableIndexes($table_name)['primary']->getColumns()[0];

  // $table_name = $db->quoteIdentifier($table_name);
  // $field_name = $db->quoteIdentifier($field_name);

  $affected_rows = $db->insert($table_name,
                               array($field_name => $field_value)
                               );

  if ( $affected_rows > 0 ) {
    $last_insert_id = $db->lastInsertId();
    // if the update is successful, add record to changelog
    $c = changelog($table_name,
                   $key_field,
                   $last_insert_id,
                   $field_name,
                   NULL,
                   $field_value
                   );
    return TRUE;
  }
  return FALSE;
}

/*
 * Delete a record.
 *
 * Strictly speaking you can feed in any field, but you should be submitting the key field and the key value. If you submit a non-key field this function might delete multiple records.
 *
 * @param string table_name The name of the table that will have the deletion.
 * @param string field_name The name of the field to use in the where clause of the sql. Should be the key field in most cases.
 * @param string field_value. The value for the where clause of the sql. Should be the database id in most cases.
 *
 * @return boolean True if at least one successful deletion occurs.
 */
function deleteRecord($table_name, $field_name, $field_value)
{
  global $db;

  $return_boolean = FALSE;

  // get primary key
  $sm = $db->getSchemaManager();
  $key_field = $sm->listTableIndexes($table_name)['primary']->getColumns()[0];
  
  $field_name_q = $db->quoteIdentifier($field_name);

  // search for matching records; save primary keys for matches
  $q = $db->createQueryBuilder();
  $q->select($key_field);
  $q->from($table_name);
  $q->where($field_name_q . ' = :value' );
  $q->setParameters( array(':value' => $field_value) );
  $r = $q->execute()->fetchAll();

  // return $r;
  // loop through matches and delete
  foreach( $r as $key => $data ){
    $this_id = $data[$key_field];
    $affected_rows = $db->delete($table_name,
                                 array($key_field => $this_id)
                                 );

    // if delete is successful do changelog
    if ($affected_rows > 0) {
      $c = changelog($table_name,
                     $key_field,
                     $this_id,
                     $key_field,
                     $this_id,
                     'record deleted'
                     );
      $return_boolean = TRUE;
    }
  }

  return $return_boolean;
}




?>