<?php

include_once 'all_pages.php';
include_once 'functions_database.php';


$sm = $db->getSchemaManager();
$events_fields = $sm->listTableColumns('events');
$events_primary_key = $sm->listTableIndexes('events')['primary']->getColumns()[0];

/**
 * Returns all events within date range.
 *
 * @param 
 *
 * @return string JSON formatted object as specified by FullCalendar.
 */
function eventsFetchDateRange($start_date = FALSE, $end_date = FALSE)
{
  global $db;
  $return_array = array();

  $q = $db->createQueryBuilder();
  $q->select('*');
  $q->from('events');
  // TODO make these not be sqlite specific datetime functions; how?
  $q->where('datetime(datetime_start) > datetime(:start_date)');
  $q->andWhere('datetime(datetime_start) < datetime(:end_date)');

  $q->setParameter(':start_date', $start_date);
  $q->setParameter(':end_date',   $end_date);

  $r = $q->execute()->fetchAll();

  // convert the event records into FullCalendar json object format?

  $return_array = $r;
  return $return_array;
}
