<?php
include '../lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'grants';
$this_id = grabString('id');

// include './lib/all_pages.php';
include 'shots/entities/grants.php';


// an example of using an array with the SQL IN operator
// this is NOT really part of DBAL. boo.
// adapted from: http://inchoo.net/dev-talk/array-parameter-dbal/

// $id_array = array(1, 2);
// $q = $db->createQueryBuilder();
// $q->select('*');
// $q->from('grants');
// $q->where('grant_id IN (?)');
// $q->setParameter( 0, $id_array, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY );
// $r = $q->execute();
// while ($record = $r->fetch()) {
//   print_r($record);
// }

// get data for one row
// $q = $db->createQueryBuilder();
// $q->select('*');
// $q->from($this_table);
// // hmmm DBAL does not seem to want to parameterize the field name
// // this kludge can work and i guess it is trustworthy cause primary key comes
// // out of the database?
// $q->where( $primary_key .' = :key_value' );
// $q->setParameters( array(
//                          ':key_value'   => $this_id
//                          ) 
//                   );
// $r = $q->execute();





$my_grants = grantsFetch( array($this_id) );

$all_html = '';

if (!empty($my_grants)){
  foreach ($my_grants as $grant_id => $data) {
    foreach ($data as $key => $value) {
      $html = grantsCreateFieldHtml($key, $value);
      $all_html .= $html;
    }
  }
}

include_once 'shots/relationships/relationships.php';

$related_entities = relationshipsFetch('grants', $grant_id, 'php');

?>



<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div id="main-entity" class="col-md-8">
      <div class="form-horizontal">
        <div class="record" data-entity-name="grants">
          <div class="fields">
          <?php echo $all_html; ?>
          </div>
          <div class="row">
            <div class="col-xs-2 col-xs-offset-10">
              <div class="form-group"> 
                <button type="button" class="btn btn-default" id="delete-button">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div id="related-entities" class="col-md-3">
      <div id="related-entities-accordian" class="panel-group">
        <?php include 'widget_related_entities.php' ?>
      </div>
    </div>
  </div>

<?php include 'html_footer.php'; ?>
</div>

<!-- include javascript scripts -->
<script type="text/javascript">
  $(document).ready(function() {
    console.log('ready');

    $('input').change( ajaxChange );

    $('#delete-button').click( openDeleteModal );

    $('.related-entities.panel-collapse').on('show.bs.collapse', revealRelatedEntities );

  }); // end document ready
</script>
</body>
</html>