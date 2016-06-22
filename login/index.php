<?php
ob_start();
include('../head.php');
$success = false;;
$try = false;
$name = "";
$message = "";
$return = $_GET['return'];
if (isset($_POST['name'])) {
    $try = true;
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $stay = $_POST['stay'];
    $res = $user->Login($name, $pass, $stay);
    if ($res == true) {
        $success = true;
    } else {
        $success = false;
        $message = "Incorrect username or password.";
    }
} else if (isset($_GET['logout'])) {
    $user->Logout();
    $success = true;
}

if ($success) {
    ob_end_clean();
    header('Location: https://ngardnerdev.com/board/' . $return);
}

ob_end_flush();
echo $twig->render('login.html', array(
    'loggedIn' => $user->LoggedIn(),
    'name' => $name,
    'message' => $message
));
include('../tail.php');
