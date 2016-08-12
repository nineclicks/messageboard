<?php
ob_start();
include_once('../model/page.php');
$page = new Page;
echo $page->Head();

$db = $page->GetDB();
$user = $page->GetUser();
$twig = $page->GetTwig();

$name = "";
$pass = "";
$cpass = "";
$message = "";
if (isset($_POST['name'])) {
    // Check captcha
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    if (!isset($_POST['g-recaptcha-response']) || 
        !CaptchaResponse($_POST['g-recaptcha-response'])) {
            $message = "Please confirm you are not a robot.";

        } else if(CheckUsername($name) == false) {
            $message = "Username must be 4 to 20 characters, numbers, letters and dashes or underscores. No sequential underscores or dashes.";
        } else if (strlen($pass) < 8){
            $message = "Password must be at least 8 characters.";
        } else if ($pass != $cpass) {
            $message = "Passwords do not match.";
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT, array('cost'=>10));
            if ($user->Signup($name,$hash) == true) {
                $db->Log('Signup success, user: ' . $name);
                ob_end_clean();
                header('Location: ' . getenv('BOARD_PATH'));
                exit();
            } else {
                $message = "Username is already taken.";
            }
        }
}
$db->Log('Signup failed, user: ' . $name);

ob_end_flush();
echo $twig->render('signup.html', array(
    'loggedIn'  => $user->LoggedIn(),
    'name'      => $name,
    'message'   => $message
));


echo $page->Tail();

function CheckUsername($name) {
    if (strlen($name) > 20 || strlen($name) < 4)
        return false;
    $clean = preg_replace('/[^a-zA-Z0-9_-]/',"",$name);
    $clean = preg_replace('/(\-\-|__)/',"",$clean);
    if ($clean === $name)
        return true;
    return false;
}

function CaptchaResponse($response) {
    $url = 'https://google.com/recaptcha/api/siteverify';
    $data = array(
        'secret'        => getenv('CAPTCHA_KEY'),
        'response'      => $response);
    $options = array(
        'http'  => array(
            'header'    => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'    => 'POST',
            'content'   => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    $result = json_decode(file_get_contents($url, false, $context), true);
    return $result['success'];
}
