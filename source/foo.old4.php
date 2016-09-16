<?php
// include './lib/all_pages.php';

// include 'html_doctype.php';
// include 'html_head.php';

// $this_table = 'grants';
// $this_id = grabString('id');

// include './lib/all_pages.php';
// include 'shots/entities/grants.php';

// $foo = getLookups('grants', 'status');
// $bax = getLookups('grants', 'baz');

// $my_grants = grantsFetch( array($this_id) );

// $bb = array_column($foo, 'lookup_value');

// $cc = array_search('bobo', array_column($foo, 'lookup_value'));

// var_dump($cc);

// $count = 3;

// global $db, $grants_primary_key;

//   $q = $db->createQueryBuilder();
//   $q->select('key_field');
//   $q->from('changelog');
//   $q->where('key_field = :key_field');
//   $q->groupBy('key_value');
//   $q->orderBy('change_timestamp', 'DESC');
//   $q->setMaxResults( $count );

//   $q->setParameters( array(':key_field' => $grants_primary_key) );

//   $r = $q->execute()->fetchAll();

//   if ( !empty($r) ){
//     $f = array_column($r, 'key_field');
//     if ( !empty($f) ){
//       $baz = grantsFetch($f, $return_format);
//     }
//   }
// $r = grantsFetchRecent(900);



// include_once 'shots/relationships/relationships.php';
// $related_entities = relationshipsFetch('grants', $grant_id, 'php');

// include_once 'shots/entities/documents.php';
// $foo = TRUE + FALSE;
// var_dump($foo);
// $foo = documentsDeactivate( array("name" => "IMG_20150813_164647845",
//                                   "extension" => "jpg",
//                                   "size" => 27467),
//                             TRUE
//                             );

function encode_in_place (&$value) {
  if ( is_array($value) ){
    $value = array_walk_recursive($value, 'encode_in_place');
  }
  $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
}

$foo = array("foo" => array("<script>console.log('gotcha');</script>", 
                            18, 
                            FALSE, 
                            NULL, 
                            TRUE
                            ),
             "bar" => array("<h1>INJECTED</h1>",
                            "hi",
                            "TRUE"
                            ),
             );

print_r($foo);

encode_in_place($foo);

print_r($foo);



?>



<body>

<?php include 'html_navbar.php'; ?>



<?php include 'html_footer.php'; ?>


<!-- include javascript scripts -->
<script type="text/javascript">
  $(document).ready(function() {
    console.log('ready');

    /**********************************************************/

    // Page Setup Code

    /**********************************************************/



    /**********************************************************/

    // Event Listeners

    /**********************************************************/

    

  }); // end document ready
</script>
</body>
</html>