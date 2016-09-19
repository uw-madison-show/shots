<?php
include 'lib/all_pages.php';

include 'shots/relationships/relationships.php';


// encode_in_place($foo);
// array_walk_recursive($foo, 'encode_in_place');

$from_entity_type = 'grants';
$from_entity_id = 3;
$to_entity_type = 'people';
$to_entity_id = 1;
$relationship_type = 'is_related_to';


// $q = $db->createQueryBuilder();
//   $q->select('count(*)')
//     ->from('relationships')
//     ->where('from_entity_type = :from_entity_type')
//     ->andWhere('from_entity_id = :from_entity_id')
//     ->andWhere('to_entity_type = :to_entity_type')
//     ->andWhere('to_entity_id = :to_entity_id')
//     ->andWhere('relationship_type = :relationship_type')
//     ;

//   $q->setParameters( array(':from_entity_type'  => $from_entity_type,
//                            ':from_entity_id'    => $from_entity_id,
//                            ':to_entity_type'    => $to_entity_type,
//                            ':to_entity_id'      => $to_entity_id,
//                            ':relationship_type' => $relationship_type,
//                            )
//                     );

//   $r = $q->execute()->fetchAll();

$foo = relationshipsAdd($from_entity_type, $from_entity_id, $to_entity_type, $to_entity_id, 'is_related_to');

include 'html_footer.php';
?>

