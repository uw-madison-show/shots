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

?>