<?php


include './lib/all_pages.php';

$db->insert('people', array('email' => 'ppeppard@wisc.edu', 
                            'name' => 'Paul Peppard',
                            'address' => '611a Warf Office Building',
                            'phone' => '(608)262-2680',
                            'affiliation' => 'UW-Madison, SHOW, SMPH, PHS',
                            'category' => 'Associate Professor',
                            'is_primary_investigator' => true,
                            )
            );

$db->insert('people', array('email' => 'bob@loblaw.com', 
                            'name' => 'Bob Loblaw',
                            'address' => '11 Law Lane',
                            'phone' => '(999)555-1234',
                            'affiliation' => 'Loblaw Law Blog',
                            'category' => 'Paralegal',
                            'is_primary_investigator' => false,
                            )
            );

