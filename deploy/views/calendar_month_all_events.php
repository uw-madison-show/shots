<?php

include '../lib/all_pages.php';
include 'html_doctype.php';
include 'html_head.php';

// include './lib/shots/entities/events.php';
// $foo = eventsFetchDateRange('2016-07-31',
//                             '2016-09-11'
//                             );

?>

<body>

  <?php include 'html_navbar.php'; ?>

  <div id="my_cal"></div>

  <?php include 'html_footer.php'; ?>

  <script type="text/javascript">
  $(document).ready(function() {

    function convertToFullCalendarEvent(input) {
      console.log(input);
      // global_foo = input;
      // if input is not an array of objects then error?

      // TODO this should be a class?
      var EventObject = {
        "id": "",
        "title": "",    // required
        "allDay": null, // boolean, allDayDefault
        "start": "",    // required, moment.js format
        "end": "",      // optional, moment.js format
        "url": "",
        "className": "",
        "editable": true,
        "startEditable": true,
        "durationEditable": true,
        "rendering": "",
        "overlap": true,
        // "constraint": "", // passing a blank string makes it stop working
        "color": "",
        "backgroundColor": "",
        "textColor": ""
      };

      var return_array = [];
      
      // loop over each object in the input array
      $.each(input, function(index, this_input){
        var this_event_object = {};

        // loop over all properites in the default EventObject
        $.each(EventObject, function(key, value){
          if ( this_input.hasOwnProperty(key) ){
            // if there is an exact mathc between input and EventObject just use it and move to the next property
            this_event_object[key] = this_input[key];
            return true;
          } else {
            // these are the idiosyncratic rules for converting database fields into the object properties that FullCalendar expects
            switch(key) {
              case 'id':
                if (this_input.repeat_id) {
                  this_event_object.id = 'R' + this_input.repeat_id;
                } else {
                  this_event_object.id = this_input.event_id;
                }
                break;
              case 'title':
                if (this_input.title === '') {
                  this_event_object.title = 'no title';
                }
                break;
              case 'allDay':
                if (this_input.all_day === 'true') {
                  this_event_object.allDay = true;
                } else if (this_input.all_day === 'false') {
                  this_event_object.allDay = false;
                } else {
                  this_event_object.allDay = EventObject.allDay;
                }
                break;
              case 'start':
                if (this_input.datetime_start !== ''){
                  this_event_object.start = this_input.datetime_start;
                } else {
                  // this is a non-sense default, but i guess it should never happen
                  this_event_object.start = EventObject.start;
                }
                break;
              case 'end':
                if (this_input.all_day === 'true') {
                  this_event_object.end = '';
                } else if (this_input.datetime_end !== '') {
                  this_event_object.end = this_input.datetime_end;
                } else {
                  this_event_object.end = EventObject.end;
                }
                break;
              case 'url':
                this_event_object.url = app_root + '/views/one_events.php?id=' + this_input.event_id;
                break;
              case 'className':
                this_event_object.className = EventObject.className;
                break;
              case 'editable':
                this_event_object.editable = EventObject.editable;
                break;
              case 'startEditable':
                this_event_object.startEditable = EventObject.startEditable;
                break;
              case 'durationEditable':
                this_event_object.durationEditable = EventObject.durationEditable;
                break;
              case 'rendering':
                break;
              case 'overlap':
                this_event_object.overlap = EventObject.overlap;
                break;
              case 'constraint':
                this_event_object.constraint = EventObject.constraint;
                break;
                // TODO abstact color, backgroundColor and textColor into its own function to pick colors based on the event type
              case 'color':
                if (this_input.type === 'outreach') {
                  this_event_object.color = 'green';
                } else if (this_input.type === 'presentation') {
                  this_event_object.color = 'orange';
                } else {
                  this_event_object.color = EventObject.color;
                }
                break;
              case 'backgroundColor':
                this_event_object.backgroundColor = EventObject.backgroundColor;
                break;
              case 'textColor':
                this_event_object.backgroundColor = EventObject.backgroundColor;
                break;
              default:
            }
          }
        });
        return_array.push(this_event_object);
      });
      console.log(return_array);
      return return_array;
    }

    function ajaxGetEvents(start, end, timezone, callback) {
      console.log(start.toISOString());
      console.log(end.toISOString());
      console.log(timezone);
      console.log(callback);
      console.log(this);
      
      // prepare ajax request
      var req = {};
      req.target   = 'entity';
      req.action   = 'eventsFetchDateRange';
      req.table    = 'events';
      req.params   = [];

      req.params.push(start.toISOString());
      req.params.push(end.toISOString());

      // make ajax request
      console.log(req);
      $.post(app_root + '/lib/ajax_handler.php',
             {"request": req},
             "json"
             )
             .done()
             .fail( ajaxFailed )
             .always(function(r){
                       if (r.error === false){
                         console.log(r.results[0]);
                         callback(convertToFullCalendarEvent(r.results[0]));
                       } else {
                         ajaxFailed();
                       }
             })



    }
    
    /**********************************************************/

    // Page Setup Code

    /**********************************************************/
    $('#my_cal').fullCalendar({

      dayClick: 
        function(date, jsEvent, view) {
          console.log(date);
          console.log(jsEvent);
          console.log(view);
          console.log(this);
        },

      eventDragStart:
        function(event, jsEvent, ui, view){
          console.log(event);
          console.log(this);
        },

      eventDrop:
        function(event, delta, revertFunc, jsEvent, ui, view){
          console.log(event);
          console.log(delta);
          console.log(this);

          if( !confirm('Are you sure?') ){
            revertFunc();
          }
        },

      allDayDefault: false,

      editable: true,

      events: function(s,e,t,c){
        ajaxGetEvents(s, e, t, c)
      }
        
      

    });
    
    /**********************************************************/

    // Event Listeners

    /**********************************************************/

  });
  </script>

</body>
</html>


