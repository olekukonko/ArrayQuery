<?php
include('data.php');
include('../src/ArrayQuery.class.php');
$loops=1;
for ($i=0; $i<$loops; $i++) {
$s = new ArrayQuery($array);
  $d = $s->find(array("release.year" => 2013));
}
print_r( $d );
?>
