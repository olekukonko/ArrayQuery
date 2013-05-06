ArrayQuery
==========
A raw PHP implementation of MongoDb-like  query expression object evaluation functions.
---------------------------------------------------------------------------------------
This class implements MongoDB-like [MongoDB-like query expression object](http://docs.mongodb.org/manual/applications/read/#find, docs.mongodb.org/manual/reference/operators/ "MongoDB manual") query expression object evaluation for PHP arrays. It may cover not all the advanced features, but has an extensible architecture.

MongoDB-like query expression objects are easy to understand and use, and provide the ability to write clean, self-explaining code, because both query and objects to search in, are associative arrays.

This class allows to use MongoDB-style queries instead of nested foreach loops making the code fast to write and easy to understand. Of course it would not be so fast as a foreach loop, so it should be used only in the non-bottlenack cases. The main benefit is that you can use the same queries for getting data in-app, from DB and in-interface(with the help of sift.js which resembles the same idea for javascript).

Basically talking its a convenient function to extract information from php arrays. Knowing the array structure(the array path), it allows to perform operations on multidimensional arrays data, without the need for multiple nested loops.

If you are not familiar with MongoDb, take a look at a given expression object and array to search in.

##USAGE:
[USAGE.md](USAGE.md)

##FAQ:
###Why not write to MongoDB? 
This is a raw PHP implementation that works without MongoDB, check out profiling information and use MongoDB in most cases(excluding those when you definetly don't need a database). 
###Why not just write the array to a MongoDB database rather than working with arrays?
This class ports some of the convinient MongoDB functionality to simplify development of PHP applications. Use the class to do some operations on preloaded data on application level, still relying on MongoDB to do the heavy database operations.
###Why is it slower then original MongoDB implementation?
Further work to make it faster on most operations may be done.

##References
This class and documentation originates from [@baba's](http://stackoverflow.com/users/1226894/baba) answers and discussion (stackoverflow)[http://stackoverflow.com/questions/14972025/implementing-mongodb-like-query-expression-object-evaluation]
