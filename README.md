ArrayQuery
==========
A raw PHP implementation of MongoDb-like  query expression object evaluation functions.
---------------------------------------------------------------------------------------
This class implements MongoDB-like [MongoDB-like query expression object](http://docs.mongodb.org/manual/applications/read/#find, docs.mongodb.org/manual/reference/operators/ "MongoDB manual") query expression object evaluation for PHP arrays. It may cover not all the advanced features, but has an extensible architecture.

MongoDB-like query expression objects are easy to understand and use, and provide the ability to write clean, self-explaining code, because both query and objects to search in, are associative arrays.

This class allows to use MongoDB-style queries instead of nested foreach loops making the code fast to write and easy to understand. Of course it would not be so fast as a foreach loop, so it should be used only in the non-bottlenack cases. The main benefit is that you can use the same queries for getting data in-app, from DB and in-interface(with the help of sift.js which resembles the same idea for javascript).

Basically talking its a convenient function to extract information from php arrays. Knowing the array structure(the array path), it allows to perform operations on multidimensional arrays data, without the need for multiple nested loops.

If you are not familiar with MongoDb, take a look at a given expression object and array to search in.

##FAQ:
###Why not write to MongoDB? 
This is a raw PHP implementation that works without MongoDB, check out profiling information and use MongoDB in most cases(excluding those when you definetly don't need a database). 
###Why not just write the array to a MongoDB database rather than working with arrays?
This class ports some of the convinient MongoDB functionality to simplify development of PHP applications. Use the class to do some operations on preloaded data on application level, still relying on MongoDB to do the heavy database operations.
###Why is it slower then original MongoDB implementation?
Further work to make it faster on most operations may be done.

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
###References
This class and documentation originates from [@baba's](http://stackoverflow.com/users/1226894/baba) answers and discussion (stackoverflow)[http://stackoverflow.com/questions/14972025/implementing-mongodb-like-query-expression-object-evaluation]
