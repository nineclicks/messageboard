<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include($root . '/board/model/db.php');
include($root . '/board/model/user.php');
$db = new DB;
$user = new User($db);
$user->TryVerify();
require $root . '/vendor/autoload.php';
$twigpath = $root . '/board/view';
$loader = new Twig_Loader_Filesystem($twigpath);
$twig = new Twig_Environment($loader);

$lexer = new Twig_Lexer($twig, array(
    'tag_block'     => array('{', '}'),
    'tag_variable'  => array('{{', '}}')
));

$twig->setLexer($lexer);
if (empty($title))
    $title = "Nicholas Gardner";
echo $twig->render('head.html', array(
    'title' => $title,
));
$loggedIn = $user->LoggedIn();
echo $twig->render('menu.html', array(
    'loggedIn'=>$loggedIn,
    'info'=>$user->GetInfo()
));
