<?php


include './lib/all_pages.php';

$db->insert('grants', array('grant_body' => 'NIH', 
                            'grant_mechanism' => 'r01',
                            'title' => 'big money grant',
                            'status' => 'funded'
                            )
            );

$db->insert('grants', array('grant_body' => 'NSF', 
                            'grant_mechanism' => 'grad student fellows',
                            'grant_number' => '1818',
                            'title' => 'big money grant',
                            'status' => 'funded'
                            )
            );