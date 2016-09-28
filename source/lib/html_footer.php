<?php

if (!function_exists('grabString')) {
  include_once 'all_pages.php';
}

?>

<footer class="footer">
  <div class="container">
    <div class="float-left smaller">
      <p>This is the page footer</p>
    </div>
    <div class="float-right smaller">
      <a href="<?php echo $app_root; ?>/manage_database.php">[DB]</a>
    </div>
    <div class="message-holder"></div>
  </div>
</footer>


<?
$debug_mode = grabString('debug');

// if i am on a test or dev server, print all the vars
// server_type is set in the settings_global.php file
if ( $server_type === 'development' ||
     ( $server_type === 'test' && $debug_mode == TRUE) ) {
  echo '<pre><code>';
  $p = print_r(get_defined_vars(), TRUE);
  echo encode($p);
  echo '</code></pre>';
}

?>