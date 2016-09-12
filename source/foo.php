<?php
include './lib/all_pages.php';

include 'html_doctype.php';
include 'html_head.php';

$this_table = 'grants';
$this_id = grabString('id');

// include './lib/all_pages.php';
include 'shots/entities/grants.php';

// $my_grants = grantsFetch( array($this_id) );


include_once 'shots/relationships/relationships.php';
// $related_entities = relationshipsFetch('grants', $grant_id, 'php');

include_once 'shots/entities/documents.php';
// $foo = TRUE + FALSE;
// var_dump($foo);
// $foo = documentsDeactivate( array("name" => "IMG_20150813_164647845",
//                                   "extension" => "jpg",
//                                   "size" => 27467),
//                             TRUE
//                             );

$foo = documentsDeactivate(array('document_id' => 5), FALSE);

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