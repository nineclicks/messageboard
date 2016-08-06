<?php
include_once('model/web.php');
include_once('model/page.php');
$page = new Page;

$db = $page->GetDB();
$user = $page->GetUser();

if ($user->LoggedIn() == false) {
    echo "error";
    exit();
}
$type = $_POST['type'];

if ($type == 'reply') {
    $parent = $_POST['id'];
    $com = htmlspecialchars(trim($_POST['replyText']));
    $date = time();
    $id = GetCode(16);
    $userName = $user->GetName();

    $newCom = array(
        'content' => $com,
        'parent' => $parent,
        'name' => $userName,
        'score' => "1",
        'date' => $date,
        'status' => '0'
    );

    $db->PostReply($id, $newCom);
    $db->Log('Comment <' . $id . '>');
    echo json_encode(array($id,$newCom));

} else if ($type == 'delete') {
    $postID = $_POST['id'];
    $db->DeletePost($postID, $user);
    echo 'ok';

} else if ($type == 'approve') {
    if ($user->GetStatus() > 1) {
        $db->ApprovePost($_POST['id']);
        echo 'ok';
    }
}
