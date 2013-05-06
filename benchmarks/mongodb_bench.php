<?php
$m = new MongoClient(); // connect
$db = $m->testmongo; // select a database
$collection = $db->data;
$loops=1;
for ($i=0; $i<$loops; $i++) {
  $d = $collection->find(array("release.year" => 2013));
}
print_r( iterator_to_array($d) );
?>
