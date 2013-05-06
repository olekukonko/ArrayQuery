##Usage:

###Array for resting 
#####[data.php](benchmarks/data.php)
```php
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
```
```php
$array = json_decode($json, true);
```

####Simple Test
```php
$s = new ArrayQuery($array);
print_r($s->find(array("release.arch"=>"x86")));
```

#####Output
```php
    Array
    (
        [0] => Array
            (
                [name] => Mongo
                [type] => db
                [release] => Array
                    (
                        [arch] => x86
                        [version] => 22
                        [year] => 2012
                    )
    
            )
    
        [1] => Array
            (
                [name] => Mongo
                [type] => db
                [release] => Array
                    (
                        [arch] => x86
                        [version] => 23
                        [year] => 2013
                    )
    
            )
    
    )
```

#### A. Support for regex

added support for $regex with alias $preg or $match which means you can have

```php
print_r($s->find(array("release.arch" => array('$regex' => "/4$/"))));
```

Or

```php
print_r($s->find(array("release.arch" => array('$regex' => "/4$/"))));
```

#####Output
```php
    Array
    (
        [1] => Array
            (
                [name] => Mongo
                [type] => db
                [release] => Array
                    (
                        [arch] => x64
                        [version] => 21
                        [year] => 2012
                    )
    
            )
    
    )
```

#### B. Use Simple array like queries
```php
    $queryArray = array(
            "release" => array(
                    "arch" => "x86"
            )
    );
    $d = $s->find($s->convert($queryArray));
```
```php    
$s->convert($queryArray)
``` 
##### converts
```php
    Array
    (
        [release] => Array
            (
                [arch] => x86
            )
    
    )
```
##### to
```php    
    Array
    (
        [release.arch] => x86
    )
```

#### C. Modulus $mod

```php
    print_r($s->find(array(
            "release.version" => array(
                    '$mod' => array(
                            23 => 0
                    )
            )
    )));
  
 //Checks release.version % 23 == 0 ;
 ```

#### D. Count elements with $size

```php
    print_r($s->find(array(
            "release.arch" => array(
                    '$size' => 2
            )
    )));

// returns count(release.arch) == 2;
```

#### E. Check if it matches all element in array $all
```php
    print_r($s->find(array(
            "release.arch" => array(
                    '$all' => array(
                            "x86",
                            "x64"
                    )
            )
    )));
```

#####Output
```php
    Array
    (
        [3] => Array
            (
                [name] => MongoBuster
                [release] => Array
                    (
                        [arch] => Array
                            (
                                [0] => x86
                                [1] => x64
                            )
    
                        [version] => 23
                        [year] => 2013
                    )
    
                [type] => db
            )
    
    )
```

#### F. If you are not sure of the element key name then you ca use $has its like the opposite of $in
```php
    print_r($s->find(array(
            "release" => array(
                    '$has' => "x86"
            )
    )));
```
