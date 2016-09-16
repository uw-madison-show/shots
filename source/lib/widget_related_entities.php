<?php
// entities comes from settings_global.php
// related_entities is the result of the relationshipsFetch() function
if (isset($entities) && isset($related_entities)) {
  // TODO loop through all entity types not just the ones with data
  $entity_list = array_keys($entities);

  echo '<p><strong>Related Records</strong></p>';

  foreach ($entity_list as $key => $this_entity) {

    $this_entity_related_count = 0;
    if ( !empty($related_entities[$this_entity]) ){
      $this_entity_related_count = count($related_entities[$this_entity]);
    }

    echo '
      <div class="panel panel-default">
        <div class="related-entities-heading panel-heading" data-entity-name="'. $this_entity .'">
          <h4 class="panel-title">
            <a class="toggle-related-entities" data-toggle="collapse" data-parent="#related-entities-accordion" href="#collapse-' . $this_entity .'">
            ' . $this_entity . ' <span class="badge">'. $this_entity_related_count .'</span></a>
            <button class="related-entities-button related-entities-button-add btn btn-xs btn-default">
              <span class="glyphicon glyphicon-plus"></span>
            </button>
          </h4>
        </div>
    ';

    if ( $this_entity_related_count === 0 ){
      // show a "blank" header and + button
    } else {
      // TODO put counters or pills on each panel heading
      // TODO add a + button for adding relationships

      // make the body of the collapsable element
      echo '
        <div id="collapse-'. $this_entity .'" class="related-entities panel-collapse collapse">
          <div class="panel-body">
      ';

      // make a unordered list with the related entities
      echo '<ul id="related-'. $this_entity .'-list" class="related-list">';
      foreach ($related_entities[$this_entity] as $key => $rel){
        echo '<li id="related-'. $this_entity . '-list-item-'. $key .'" class="related-list-item" data-entity="'. $this_entity .'" data-entity-id="'.$rel['id'] .'"><a href="'. $app_root .'/views/one_'. $this_entity .'.php?id='. $rel['id'] .'">'. $rel['id'] . '</a></li>';
      }
      echo '</ul>';
      
      echo '</div>'; // close panel-body;
      echo '</div>'; // close panel-collapse;
    } // end if related_entities is empty for this_entity
    echo '</div>'; // close panel
  } // end foreach across entity_list
} // end if entities and related_entities are set

?>

<script>
  $('.related-entities-button').on('click', function(e) {
    // ego = the main entity; i.e. the one that will get a thing attached to it
    // alter = the entity to be attached; at this point we probably only name the name of the entity type for the alter
    var ego_entity_name = $('#main-entity .record').data('entityName');
    var ego_key_field   = key_field_mapping[ego_entity_name];
    var ego_key_value   = $('#' + ego_key_field).val();
    var alter_data      = $(this).parents('.related-entities-heading').data();


    console.log(ego_entity_name);
    console.log(ego_key_value);
    console.log(alter_data);

    // quick search of all entities of this type
    // this should leave out the entities of this type that are already attached to ego

    // open a modal 
    var a = openAttachModal( ego_entity_name, ego_key_value, alter_data.entityName );


    // TODO maybe a type ahead input?

    // update the relationship table

    // reload the page

  });
</script>