<!DOCTYPE html>
<html>
<head>
  <title>Grant</title>
</head>

<!-- database stuff -->
<?php

include './lib/all_pages.php';

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

$this_table = 'grants';
$this_id = 1;


// get the schema
$sm = $db->getSchemaManager();
$columns = $sm->listTableColumns($this_table);
$primary_key = $sm->listTableIndexes($this_table)['primary']->getColumns()[0];



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
$q = $db->createQueryBuilder();
$q->select('*');
$q->from($this_table);
// hmmm DBAL does not seem to want to parameterize the field name
// this kludge can work and i guess it is trustworthy cause primary key comes
// out of the database?
$q->where( $primary_key .' = :key_value' );
$q->setParameters( array(
                         ':key_value'   => $this_id
                         ) 
                  );
$r = $q->execute();

var_dump($r);

while ( $record = $r->fetch() ){
  var_dump($record);
}







?>



<body>

<!-- view or edit -->

<!-- errors or expert settings -->
</body>


<pre>
<?php print_r(get_defined_vars()); ?>
</pre>




<!-- include javascript scripts -->



</html>