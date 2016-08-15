<?php

include './lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';


?>

<body>

  <?php include 'html_navbar.php'; ?>

  <div id="my_cal"></div>

  <?php include 'html_footer.php'; ?>

  <script type="text/javascript">
  $(document).ready(function() {
    
    /**********************************************************/

    // Page Setup Code

    /**********************************************************/
    $('#my_cal').fullCalendar({

      'dayClick': 
        function(date, jsEvent, view) {
          console.log(date);
          console.log(jsEvent);
          console.log(view);
          console.log(this);
        },

      'eventDragStart':
        function(event, jsEvent, ui, view){
          console.log(event);
          console.log(this);
        },

      'eventDrop':
        function(event, delta, revertFunc, jsEvent, ui, view){
          console.log(event);
          console.log(delta);
          console.log(this);

          if( !confirm('Are you sure?') ){
            revertFunc();
          }
        },


      'editable': true,

      'events': {
        'url': '/lib/ajax_handler.php',
        'type': 'POST',
        'data': {
                  'request': { 
                    'target': 'entity',
                    'action': 'eventsFetchDateRange',
                    'table':  'events',
                    'params': ['2016-08-01', '2016-08-31']
                  }
                },
        'error': function(){
          console.log(this);
        },
        'color': 'red'
      }
        
      

    });
    
    /**********************************************************/

    // Event Listeners

    /**********************************************************/

  });
  </script>

</body>
</html>


