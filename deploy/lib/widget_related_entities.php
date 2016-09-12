<?php
  // TODO loop through all entity types not just the ones with data
  // TODO put counters or pills on each panel heading
  // TODO add a + button for adding relationships
  foreach ($related_entities as $entity_type => $relationships){
    echo '<div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="toggle-related-entities" data-toggle="collapse" data-parent="#related-entities-accordion" href="#collapse-' . $entity_type .'">
                ' . $entity_type . '</a>
              </h4>
            </div>
            <div id="collapse-'. $entity_type .'" class="related-entities panel-collapse collapse">
              <div class="panel-body">
          ';

    echo '<ul id="related-'. $entity_type .'-list" class="related-list">';
    foreach ($relationships as $key => $rel) {
      echo '<li id="related-'. $entity_type . '-list-item-'. $key .'" class="related-list-item" data-entity="'. $entity_type .'" data-entity-id="'.$rel['id'] .'"><a href="'. $app_root .'/views/one_'. $entity_type .'.php?id='. $rel['id'] .'">'. $rel['id'] . '</a></li>';
    }
    echo '</ul>';

    echo '</div>'; // close panel-body;
    echo '</div>'; // close panel-collapse;
    echo '</div>'; // close panel
  }
?>