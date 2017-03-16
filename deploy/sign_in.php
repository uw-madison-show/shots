<?php
include './lib/all_pages.php';
include_once 'functions_database.php';

// TODO test if I have username, if yes redirect to home
// echo "<h1>" . $username . "</h1>";
if (!empty($username)){
  header('Location: ' . $app_root . '/');
}

include 'html_doctype.php';
include 'html_head.php';

?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2"><span></span></div>
    <div id="main-entity" class="col-sm-6">
      
      <h2>SHOW Output Tracking System</h2>
      <h3>Survey of the Health of Wisconsin</h3>
      <p>Please log in with a Google account. For our UW-Madison users, we strongly recommend using the Google Apps account linked to your @wisc.edu account. If you have questions contact ...</p>
        
    </div>
  </div>
</div> <!-- close container-fluid -->

<?php include 'html_footer.php'; ?>

<script type="text/javascript">  
</script>

</body>
</html>


