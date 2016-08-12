<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

include 'functions_database.php';

trigger_error('test error message');

try{
  throw new Exception("test exception message");
} catch (Exception $e) {
  trigger_error($e);
}

$str = 'first_second_third';

$foo = preg_replace_callback('/_(.?)/', function($matches) { return strtoupper($matches[1]); }, $str);
?>

  <?php include 'html_navbar.php'; ?>

  <?php include 'html_footer.php'; ?>

  <script type="text/javascript">

    // this isn't done yet TODO finish it
    $(document).ready(function() {

      // foo = initializeTable('internals', 'lookup_values', 'lookup_value_id');

      

      // var test_vals = ['', {}, {'key': 'value'}, {null: null}, [], 1, '11', {'key': false}, false, true, 'false', 'true',{ill:'formed',javascript:'object'}, '{"good": "object in a string", "1": 27.3}', NaN, null, function(){}, [null, null, NaN], [true, true], [1, 2, 3] ];

      // var results = [];

      // for (var i = test_vals.length - 1; i >= 0; i--) {
      //   console.log(test_vals[i]);
      //   var test_one = $.isEmptyObject(test_vals[i]);
      //   var test_two = test_vals[i] !== null ? test_vals[i].length : 'null';
      //   var test_three = typeof test_vals[i] === 'object';

      //   var label = typeof test_vals[i] === 'object' ? 'object => ' + JSON.stringify(test_vals[i]) : test_vals[i];

      //   var this_result = [ label , test_one, test_two, test_three ];
      //   results[i] = this_result;
      // }

      // console.table(results);
      

      
    });
  </script>

</body>
</html>


