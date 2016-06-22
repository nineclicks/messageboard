<?php
ob_start();
include('../head.php');
$name = "";
$message = "";

if (isset($_POST['name'])) {
    if (isset($_POST['g-recaptcha-response']) && 
        CaptchaResponse($_POST['g-recaptcha-response'])) {
            $message = "Captcha ok";
        } else {
            $message = "Please confirm you are not a robot.";
        }

}

echo $twig->render('signup.html', array(
    'loggedIn' => $user->LoggedIn(),
    'name' => $name,
    'message' => $message
));


include('../tail.php');


function CaptchaResponse($response) {
    include('/var/www/pw.php');
    $url = 'https://google.com/recaptcha/api/siteverify';
    $data = array(
        'secret'   => $pass['captcha'],
        'response' => $response);
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    $result = json_decode(file_get_contents($url, false, $context), true);
    return $result['success'];
}
