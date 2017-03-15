<?php
include './lib/all_pages.php';
include_once 'functions_database.php';
include_once 'functions_utility.php';

include 'html_doctype.php';
include 'html_head.php';


?>

<body>

<?php include 'html_navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12" role="main" id="main">

      <p>hello world</p>

      <?php
        $strings = array(1488357000,
                         '2017-03-17',
                         '2017-03-18 12:30:00',
                         '2017-01-31 22:45',
                         '2017-03-03 2:32 A.M. CDT',
                         '2017-02-28T00:00:00CDT',
                         '2017-03-03 2pm',
                         '2017-03-03 2am',
                         '2017-03-03 2:30 P.M. EST',
                         array('2017', '3', '15', '12', '18'),
                         );

        $force_timezone = new DateTimeZone('America/Chicago');

        foreach ($strings as $this_string) {
          if ( is_string($this_string) ){
            echo '<div><h3>'. $this_string . '</h3></div>';
          }
          // try{
          //   $datetime_object = new DateTime($this_string);

          //   echo '<div> datetime object </div>';

          //   echo '<div><pre>';
          //   var_dump($datetime_object);
          //   echo '</pre></div>';

          // } catch (Exception $e) {
          //   echo 'error: '. $e->getMessage();
          // }

          $my_func = handleDateString($this_string, 'DateTime');

          echo '<div> My Function </div>';

          echo '<div><pre>';
          var_dump($my_func);
          echo '</pre></div>';
        }
      ?>

    </div>
  </div>
</div>

<?php include 'html_footer.php'; ?>

<script type="text/javascript">
  // page specific js goes here
</script>
</body>
</html>

