<?php

include_once 'all_pages.php';
include_once 'functions_database.php';

// get schema info
$sm                        = $db->getSchemaManager();
$relationships_fields      = $sm->listTableColumns('relationships');
$relationships_primary_key = $sm->listTableIndexes('relationships')['primary']->getColumns()[0];

/**
 * Returns all relationships that match the type and id.
 *
 * Feed in the entity type and entity id.
 *
 * @param string $entity_type The name of the entity, e.g., 'people', 'grants', etc.
 * @param string $entity_id The id of the focal entity.
 * @param string $format The format of the return object. Default is "php"; will add "json" function.
 *
 * @return array Nested array of all related entities. First level is the type of entity, and the second level is the entity ID.
 */
function relationshipsFetch( $entity_type = false, $entity_id = false, $format = 'php' )
{
  global $db, $relationships_primary_key;
  $return_array = array();
  
  // look for parent relationships
  $q1 = $db->createQueryBuilder();
  $q1->select($relationships_primary_key, 'relationship_type', 'from_entity_type', 'from_entity_id');
  $q1->from('relationships');
  $q1->where( 'to_entity_type = :type' );
  $q1->andWhere( 'to_entity_id = :id' );
  $q1->setParameters( array(':type' => $entity_type,
                            ':id'   => $entity_id
                            ));

  $r_parents = $q1->execute()->fetchAll();

  foreach ($r_parents as $key => $rel) {
    if ( !in_array($rel['from_entity_type'], array_keys($return_array)) ) {
      $return_array[$rel['from_entity_type']] = array();
    }
    $return_array[$rel['from_entity_type']][] = array('id'        => $rel['from_entity_id'],
                                                      'type'      => $rel['relationship_type'],
                                                      'direction' => 'parents',
                                                      'relationship_id' => $rel[$relationships_primary_key]
                                                      );
  }

  // look for child relationships
  $q2 = $db->createQueryBuilder();
  $q2->select($relationships_primary_key, 'relationship_type', 'to_entity_type', 'to_entity_id');
  $q2->from('relationships');
  $q2->where( 'from_entity_type = :type' );
  $q2->andWhere( 'from_entity_id = :id' );
  $q2->setParameters( array(':type' => $entity_type,
                            ':id'   => $entity_id
                            ));

  $r_children = $q2->execute()->fetchAll();

  foreach ($r_children as $key => $rel) {
    if ( !in_array($rel['to_entity_type'], array_keys($return_array)) ) {
      $return_array[$rel['to_entity_type']] = array();
    }
    $return_array[$rel['to_entity_type']][] = array('id'        => $rel['to_entity_id'],
                                                    'type'      => $rel['relationship_type'],
                                                    'direction' => 'children',
                                                    'relationship_id' => $rel[$relationships_primary_key]
                                                    );
  }

  return $return_array;
}

/**
 * Add a row to the relationship table.
 *
 * Can be used to relate any two records in the database.
 *
 * @param string $from_entity_type e.g. 'people'
 * @param string $from_entity_id The value of the primary key of the entity table specified in $from_entity_type.
 * @param string $to_entity_type e.g. 'documents'
 * @param string $to_entity_id The value of the primary key of the entity table speicifed in $to_entity_type
 * @param string $relationship_type Defaults to 'is_related_to'. Currently not used in queries or reports, but different types of relationships could be used for customization.
 *
 * @return boolean TRUE when a relationship is added an there are no errors. FALSE when there is an error or if the relationship was not added, e.g. it already existed.
 */

function relationshipsAdd($from_entity_type = FALSE, $from_entity_id = FALSE, $to_entity_type = FALSE, $to_entity_id = FALSE, $relationship_type = 'is_related_to')
{
  // $timer_start = microtime(true);
  global $db;

  if (!$from_entity_type or !$from_entity_id or !$to_entity_type or !$to_entity_id) {
    trigger_error('Missing parameters from relationshipsAdd().');
    return FALSE;
  }

  // test if entity types are valid table names
  $sm = $db->getSchemaManager();
  $t = $sm->listTables();
  $table_list = array();
  foreach ($t as $key => $this_table) {
    $table_list[] = $this_table->getName();
  }

  if ( !in_array($from_entity_type, $table_list) ){
    trigger_error($from_entity_type . ' is not a valid entity.');
    return FALSE;
  }

  if (!in_array($to_entity_type, $table_list) ){
    trigger_error($to_entity_type . ' is not a valid entity.');
    return FALSE;
  }
  // $checks_done = microtime(true);

  // test if the id values exist in the entity tables;
  $from_record = getRecord($from_entity_type, $from_entity_id);
  $to_record   = getRecord($to_entity_type,   $to_entity_id);

  if (empty($from_record)) {
    trigger_error('Could not find ID '. $from_entity_id . ' in table ' . $from_entity_type);
    return FALSE;
  }

  if (empty($to_record)) {
    trigger_error('Could not find ID '. $to_entity_id . ' in table ' . $to_entity_type);
    return FALSE;
  }
  // $getRecord_done = microtime(true);

  // check if this relationship already exists

  // insert the relationship
  $new_id = addRecord('relationships',
                      'from_entity_type',
                      $from_entity_type
                      );
  $addRecord_done = microtime(true);
  $ck = updateRecord('relationships',
                     array('from_entity_id'    => $from_entity_id,
                           'to_entity_type'    => $to_entity_type,
                           'to_entity_id'      => $to_entity_id,
                           'relationship_type' => $relationship_type),
                     'relationship_id',
                     $new_id
                     );
  // $updateRecord_done = microtime(true);

  // echo '<div>checks '. ($checks_done - $timer_start) . '</div>';
  // echo '<div>getRecord '. ($getRecord_done - $checks_done) . '</div>';
  // echo '<div>addRecord '. ($addRecord_done - $getRecord_done). '</div>';
  // echo '<div>updateRecord '. ($updateRecord_done - $addRecord_done) . '</div>';

  return $ck;
}