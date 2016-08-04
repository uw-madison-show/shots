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