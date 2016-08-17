<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

$foo = $_SERVER['SCRIPT_NAME'];

$bar = dirname($_SERVER['SCRIPT_FILENAME']);



?>

<body>

  <?php include 'html_navbar.php'; ?>

  <input id="my_upload" type="file" name="files[]" data-url="lib/file_handler.php" multiple/>

  <?php include 'html_footer.php'; ?>

  <script type="text/javascript">
  $(document).ready(function() {
    
    /**********************************************************/

    // Page Setup Code

    /**********************************************************/
    $('#my_upload').fileupload({
      dataType: 'json',
      done: function (e, data) {
        console.log(e);
        console.log(data);
      }
    });
    
    /**********************************************************/

    // Event Listeners

    /**********************************************************/

  });
  </script>

</body>
</html>


