<?php
include('../head.php');
echo 'user<br>';
var_dump($_GET);
echo $twig->render('notfound.html', array(
));
include('../tail.php');
