<!DOCTYPE html>
<html>
<head>
  <title>Grant</title>
</head>

<!-- database stuff -->
<?php

include './lib/all_pages.php';

// $ins = $db->insert('grants',
//                    array('grant_body'      => 'EPA',
//                          'grant_mechanism' => 'rh24',
//                          'grant_number'    => '12345',
//                          'title'           => 'Our building is a superfund site, can we make it better?',
//                          'link_to_cfp'     => 'http://epa.gov/call-for-proposals',
//                          'status'          => 'Funded'
//                          )
//                    );

$q = $db->createQueryBuilder();

$q->select('*');
$q->from('grants');
$q->where('grant_id = :grant_id');
$q->setParameters(array(':grant_id' => 1,
                        
                        )
                  );

// $r = $db->query($q);

// $r = $db->prepare($q);

$r = $q->execute();

while ($record = $r->fetch()) {
  print_r($record);
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