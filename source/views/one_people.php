<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'people';
$this_id    = grabString('id');

include_once 'shots/entities/people.php';

$results = peopleFetch( array($this_id) );

$all_html = '';

foreach ($results as $people_id => $data) {
  foreach ($data as $key => $value) {
    $html = peopleCreateFieldHtml($key, $value);
    $all_html .= $html;
  }
}

include_once 'shots/relationships/relationships.php';

$related_entities = relationshipsFetch('people', $people_id, 'php');

?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div id="main-entity" class="col-md-8">
      <div class="form-horizontal">
        <div class="record" data-entity-name="people">
          <div class="fields">
          <?php echo $all_html; ?>
          </div>
        </div>
      </div>
    </div>
    <div id="related-entities" class="col-md-3">
      <div id="related-entities-accordian" class="panel-group">
      <?php
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

          echo '<ul id="related-'. $entity_type .'-list">';
          foreach ($relationships as $key => $rel) {
            echo '<li id="related-'. $entity_type . '-list-item-'. $key .'" data-entity="'. $entity_type .'" data-entity-id="'.$rel['id'] .'"><a href="/views/one_'. $entity_type .'.php?id='. $rel['id'] .'">'. $rel['id'] . '</a></li>';
          }
          echo '</ul>';

          echo '</div>'; // close panel-body;
          echo '</div>'; // close panel-collapse;
          echo '</div>'; // close panel
        }
      ?>
    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  $(document).ready(function() {
    console.log('ready');

    $('input').change( ajaxChange );

    $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities );


  }); // end document ready
</script>

</body>
</html>