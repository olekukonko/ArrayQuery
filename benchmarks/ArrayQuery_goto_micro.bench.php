<?php
include('../data/data_big.php');
include('../src/ArrayQuery_goto_micro.class.php');
$loops=100;
$s = new ArrayQuery_goto_micro($array);
for ($i=0; $i<$loops; $i++) {
$d = $s->find(array("release.year" => 2013));
$d = $s->find(array("release.arch"=>"x86"));
$d = $s->find(array("release.arch" => array('$regex' => "/4$/")));
$queryArray = array(
        "release" => array(
                "arch" => "x86"
        )
);
//$d = $s->find($s->convert($queryArray));
$d = $s->convert($queryArray);
$d = $s->find(array(
        "release.version" => array(
                '$mod' => array(
                        23 => 0
                )
        )
));
$d = $s->find(array(
        "release.arch" => array(
                '$size' => 2
        )
));
$d = $s->find(array(
        "release.arch" => array(
                '$all' => array(
                        "x86",
                        "x64"
                )
        )
));
$d = $s->find(array(
        "release" => array(
                '$has' => "x86"
        )
));
}
?>
