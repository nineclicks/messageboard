<?php
ob_start();
include_once('../model/page.php');
$page = new Page;
echo $page->Head();

$db = $page->GetDB();
$user = $page->GetUser();
$twig = $page->GetTwig();
$success = false;;
$try = false;
$name = "";
$message = "";
$return = $_GET['return'];
if (isset($_POST['name'])) {
    $try = true;
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $stay = (isset($_POST['stay']) && $_POST['stay']);
    $res = $user->Login($name, $pass, $stay);
    if ($res == true) {
        $db->Log('Login success, user: ' . $name);
        $success = true;
    } else {
        $db->Log('Failed login, user: ' . $name);
        $success = false;
        $message = "Incorrect username or password.";
    }
} else if (isset($_GET['logout'])) {
    $db->Log('Logout, user: ' . $user->GetName());
    $user->Logout();
    $success = true;
}

if ($success) {
    ob_end_clean();
    header('Location: ' . getenv('BOARD_PATH') . $return);
}

ob_end_flush();
echo $twig->render('login.html', array(
    'loggedIn' => $user->LoggedIn(),
    'name' => $name,
    'message' => $message
));
echo $page->Tail();
