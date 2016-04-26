<?php require '/lib/doctype.php'; ?>
<?php require '/lib/head.php'; ?>

<!-- database stuff -->
<?php

$this_table = 'grants';
$this_id    = 1;

// include './lib/all_pages.php';
include './lib/shots/entities/grants.php';


// i did a couple inserts to have some records to play with
// $ins = $db->insert('grants',
//                    array('grant_body'      => 'EPA',
//                          'grant_mechanism' => 'rh24',
//                          'grant_number'    => '12345',
//                          'title'           => 'Our building is a superfund site, can we make it better?',
//                          'link_to_cfp'     => 'http://epa.gov/call-for-proposals',
//                          'status'          => 'Funded'
//                          )
//                    );





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





$my_grants = fetchGrants( array(2) );

$all_html = '';

foreach ($my_grants as $grant_id => $data) {
  foreach ($data as $key => $value) {
    $html = createGrantFieldHtml($key, $value);
    $all_html .= $html;
  }
}



?>



<body>

<!-- view or edit -->



<?php 
echo '<div class="record">';
// echo '<input type="hidden" class="record_id" id="grant_id" name="grant_id" value="'. $my_grants[2]['grant_id'] . '" />';
echo '<div class="fields">';
echo $all_html; 
echo '</div>';
echo '</div>';
?>

<!-- errors or expert settings -->
</body>


<pre>
<?php //print_r(get_defined_vars()); ?>
</pre>




<!-- include javascript scripts -->
<script type="text/javascript">
  
  $(document).ready(function() {
    console.log('ready');

    function ajaxChange(e){
      console.log(this);

      // the ajax handler is going to include the file /lib/shots/entities/{table}.php
      // then it will use call_user_func() to pass the {params}
      // into the function named by {action}
      var req = {};
      req.action   = 'updateGrant';
      req.table    = 'grants';
      req.params   = [];

      // get record id
      // this requires the html to be marked up in a specific way
      req.params.push($(this).closest('.record').find('#grant_id').val());

      // field name and new value;
      req.params.push($(this).attr('id'));
      req.params.push($(this).val());

      console.log(req);

      $.post('/lib/ajax_handler.php',
             {
               q: req
             })
             .done( function(d){
              console.log(d);

             })
             .fail( 
               function(d){
                 console.log('ajax post failed');
                 console.log(d);
               }
             )
             .always()
             ;
    }

    $('input').change( ajaxChange );
  }); // end document ready

</script>



</html>