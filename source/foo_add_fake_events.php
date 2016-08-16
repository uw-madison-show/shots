<?php


include './lib/all_pages.php';

$db->insert('events', array('title' => 'weekly coffee', 
                            'repeat_id' => '10001',
                            'datetime_start' => '2016-08-31 12:30',
                            'datetime_end' => '2016-08-31 13:30',
                            'all_day' => 'false',
                            'type' => 'ongoing',
                            'note' => 'chat with the Warrior folks about poop samples'
                            )
            );

$db->insert('events', array('title' => 'weekly coffee', 
                            'repeat_id' => '10001',
                            'datetime_start' => '2016-09-07 12:30',
                            'datetime_end' => '2016-09-07 13:30',
                            'all_day' => 'false',
                            'type' => 'ongoing',
                            'note' => 'chat with the Warrior folks about poop samples'
                            )
            );

$db->insert('events', array('title' => 'weekly coffee', 
                            'repeat_id' => '10001',
                            'datetime_start' => '2016-09-14 12:30',
                            'datetime_end' => '2016-09-14 13:30',
                            'all_day' => 'false',
                            'type' => 'ongoing',
                            'note' => 'chat with the Warrior folks about poop samples'
                            )
            );

$db->insert('events', array('title' => 'weekly coffee', 
                            'repeat_id' => '10001',
                            'datetime_start' => '2016-09-21 12:30',
                            'datetime_end' => '2016-09-21 13:30',
                            'all_day' => 'false',
                            'type' => 'ongoing',
                            'note' => 'chat with the Warrior folks about poop samples'
                            )
            );


