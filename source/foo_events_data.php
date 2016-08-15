<?php
header("Content-Type: application/json;charset=utf-8");
echo '
[{"id":"1","title": "First Event","start": "2016-08-17"},
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
        ]
';

?>