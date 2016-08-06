<?php
include_once('random_compat/random.php');
function GetCode($count) {
    $ret = "";
    for ($i = 0; $i < $count; $i++) {
        $ret .= base62(random_int(0,61));
    }
    return $ret;
}

function base62($num) {
    $base = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    return substr($base,$num,1);
}
