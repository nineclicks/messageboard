<?php
include_once('model/page.php');
$page = new Page;
echo $page->Head("",true);

$db = $page->GetDB();
$user = $page->GetUser();
$twig = $page->GetTwig();

// Get the requested board/page/post
if (count($_GET) > 0)
    $request = explode('/',array_keys($_GET)[0]);
else
    $request = array();

// remove empty parameters
$request = array_filter($request, 'strlen');


$rcount = count($request);

if ($rcount == 0) {
    // No page requested

    $boards = $db->GetBoards();
    echo $twig->render('boards.html', array(
        'boards' => $boards
    ));
} else if($rcount < 2 || $request[1] != 'post') {
    // Board page requested

    $postsPerPage = 10;
    $board = $request[0];
    if ($rcount > 1)
        $pageNum = intval($request[1]);
    else
        $pageNum = 1;

    if ($pageNum < 1)
        $pageNum = 1;

    $posts = $db->GetBoardPosts(
        $board, 
        ($pageNum - 1) * $postsPerPage, 
        $postsPerPage,
        $user->GetID()
    );

    echo $twig->render('posts.html', array(
        'posts' => $posts,
        'board' => $board,
        'page' => $pageNum
    ));

} else if ($rcount > 2) {
    // Post requested

    $board = $request[0];
    $post = $request[2];
    $uid = $user->GetID();
    $loggedin = $user->LoggedIn();
    $mod = 0;
    if ($user->GetStatus() > 1)
        $mod = 1;
    $postArr = $db->GetPost($post, $uid, $mod);
    $userArr = $user->GetInfo(true);
    echo $twig->render('viewpost.html', array(
        'postid' => $post,
        'board' => $board,
        'comments' => json_encode($postArr),
        'loggedin' => $loggedin,
        'user' => $userArr
    ));
} else {
    // User is requesting something weird
}

echo $page->Tail();
