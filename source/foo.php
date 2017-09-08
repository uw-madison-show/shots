<?php
include './lib/all_pages.php';
include_once 'functions_database.php';
include_once 'functions_utility.php';


include 'html_doctype.php';
include 'html_head.php';

include 'shots/entities/events.php';

$foo = eventsFetch('8');

$bar = eventsValidate('8', 'date_end', '2017-06-08');

?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12" role="main" id="main">

      <h3>hello world</h3>

    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  // page specific js goes here

  var my_moment = moment();

  console.log(my_moment);

</script>
</body>
</html>

