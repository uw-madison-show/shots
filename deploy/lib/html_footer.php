<footer class="footer">
  <div class="container">
    <p>This is the page footer</p>
    <div class="message-holder"></div>
  </div>
</footer>

<?php

// if i am on a test or dev server, print all the vars
$test_machines_pattern = '/(127\\.0\\.0\\.1)|(localhost)|(wwwtest)/i';
if ( preg_match($test_machines_pattern, $_SERVER['SERVER_NAME']) === 1) {
  echo '<pre>';
  print_r(get_defined_vars());
  echo '</pre>';
}

?>