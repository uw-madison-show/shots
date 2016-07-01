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

foreach ($my_grants as $grant_id => $data) {
  foreach ($data as $key => $value) {
    $html = grantsCreateFieldHtml($key, $value);
    $all_html .= $html;
  }
}



?>



<body>

<!-- view or edit -->



<?php 
// the data-entity-name attribute is required for the ajaxChange function to know which table to update
echo '<div class="record" data-entity-name="grants">';
// echo '<input type="hidden" class="record_id" id="grant_id" name="grant_id" value="'. $my_grants[2]['grant_id'] . '" />';
echo '<div class="fields">';
echo $all_html; 
echo '</div>';
echo '</div>';
?>

<!-- errors or expert settings -->

<!-- include javascript scripts -->
<script type="text/javascript">
  
  $(document).ready(function() {
    console.log('ready');

    $('input').change( ajaxChange );
  }); // end document ready

</script>
</body>

<?php include 'html_footer.php'; ?>