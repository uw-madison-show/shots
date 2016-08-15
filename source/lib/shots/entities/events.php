<?php

include_once 'all_pages.php';
include_once 'functions_database.php';


$sm = $db->getSchemaManager();
// $events_fields = $sm->listTableColumns('events');
// $events_primary_key = $sm->listTableIndexes('events')['primary']->getColumns()[0];

/**
 * Returns all events within date range.
 *
 * @param 
 *
 * @return string JSON formatted object as specified by FullCalendar.
 */
function eventsFetchDateRange($start_date = FALSE, $end_date = FALSE)
{
  return '[{"id":"1","title": "First Event","start": "2016-08-17"},
          {
            "id":    "2",
            "title": "Second Event",
            "start": "2016-08-19",
            "end":   "2016-08-21"
          },
          {
            "id":    "3",
            "title": "Third Event",
            "start": "2016-08-22 12:30:00",
            "color": "black"
          }
        ]';
}
