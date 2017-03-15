<?php

  if (!function_exists('grabString')) {
    include_once 'all_pages.php';
  }

  $debug_mode = grabString('debug');

  $in_debug_mode = FALSE;

  // if i am on a test or dev server, i am in debug mode
  // if this page request came with the ?debug=true get parameter then i am in debug mode
  // server_type is set in the settings_global.php file
  if ( $server_type === 'development' ||
       ( $server_type === 'test' && $debug_mode == TRUE) ) {
    $in_debug_mode = TRUE;
  }

?>

<footer class="footer">
  <div class="container">
    <div class="float-left smaller">
      <p>This is the page footer</p>
    </div>
    <div class="float-right smaller">
      <?php
        if ( $in_debug_mode ){
          echo '
            <div>
              <a href="'. $app_root .'/manage_database.php">[ Database phpLiteAdmin ]</a>
            </div>
          ';
        }
      ?>
      <div>
        <p>Default SHOTS timezone is <?php echo $shots_default_timezone->getName(); ?></p>
      </div>
    </div>
    <div class="message-holder"></div>
  </div>
</footer>


<?php

  if ( $in_debug_mode ){
    echo '<pre><code>';
    $p = print_r(get_defined_vars(), TRUE);
    echo encode($p);
    echo '</code></pre>';
  }

?>