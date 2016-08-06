<?php
include('model/web.php');
include('model/db.php');
$db = new DB;
echo 'test<br>';
for ($i = 1; $i <= 32; $i = $i * 2) {
    echo $i . ": " . GetCode($i) . '<br>';
    
}

$arr = array(
'one'=>'a',
'two'=>'b',
'three'=>'c',
'four'=>'d'
);

$arr2 = array($arr['two']);
