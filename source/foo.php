<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

$new_include_path = get_include_path();


?>

<body>

<script type="text/javascript">
  var req = {};
  req.target   = 'entity';
  req.action   = 'updateGrant';
  req.table    = 'grants';
  req.params   = [];

  // get record id
  // this requires the html to be marked up in a specific way
  // req.params.push($(this).closest('.record').find('#grant_id').val());

  // field name and new value;
  // req.params.push($(this).attr('id'));
  // req.params.push($(this).val());

  console.log(req);

  $.post('/lib/ajax_handler.php', { "request": req })
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
</script>

</body>

<?php

include './lib/html_footer.php';

?>


