<?php


include './lib/all_pages.php';

$db->insert('relationships', array('from_entity_type'  => 'ancillary_studies', 
                                   'from_entity_id'    => '1',
                                   'relationship_type' => 'run_by',
                                   'to_entity_type'    => 'people',
                                   'to_entity_id'      => '2'
                                   )
            );

include 'html_footer.php';

