<?php
$json = '[
  {
    "name": "Mongo",
    "release": {
      "arch": "x86",
      "version": 22,
      "year": 2012
    },
    "type": "db"
  },
  {
    "name": "Mongo",
    "release": {
      "arch": "x64",
      "version": 21,
      "year": 2012
    },
    "type": "db"
  },
  {
    "name": "Mongo",
    "release": {
      "arch": "x86",
      "version": 23,
      "year": 2013
    },
    "type": "db"
  },
  {
    "name": "MongoBuster",
    "release": {
      "arch": [
        "x86",
        "x64"
      ],
      "version": 23,
      "year": 2013
    },
    "type": "db"
  },
  {
    "children": {
      "dance": [
        "one",
        "two",
        {
          "three": {
            "a": "apple",
            "b": 700000,
            "c": 8.8
          }
        }
      ],
      "lang": "php",
      "tech": "json",
      "year": 2013
    },
    "key": "Different",
    "value": "cool"
  }
]';

$array = json_decode($json, true);
?>
