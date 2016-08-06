<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once($root . '/board/model/db.php');
include_once($root . '/board/model/user.php');
include_once($root . '/board/model/web.php');

class Page {
    private $twigLoaded = false;
    private $userLoaded = false;
    private $user;
    private $db;
    private $twig;
    public function __construct() {

    }

    public function Head($title = "", $ret = false) {
        $this->LoadUser();
        $this->LoadTwig();

        $returnStr = "";
        $returnStr .= $this->twig->render('head.html', array(
            'title' => $title,
        ));
        $loggedIn = $this->user->LoggedIn();

        $rt = '/board/';
        $uri = substr($_SERVER['REQUEST_URI'],strlen($rt));
        if ($ret && !empty($uri)) {
            $returnTo = '?return=' . $uri;
        } else {
            $returnTo = '';
        }

        $returnStr .= $this->twig->render('menu.html', array(
            'loggedIn'=>$loggedIn,
            'info'=>$this->user->GetInfo(),
            'returnTo'=>$returnTo
        ));

        return $returnStr;
    }

    public function Tail() {
        return $this->twig->render('tail.html', array(
        ));
    }

    public function GetUser() {
        $this->LoadUser();
        return $this->user;
    }

    public function GetDB() {
        $this->LoadUser();
        return $this->db;
    }

    public function GetTwig() {
        $this->LoadTwig();
        return $this->twig;
    }

    private function LoadTwig() {
        global $root;
        if ($this->twigLoaded == true)
            return;
        $this->twigLoaded = true;

        require_once $root . '/vendor/autoload.php';
        $twigpath = $root . '/board/view';
        $loader = new Twig_Loader_Filesystem($twigpath);
        $this->twig = new Twig_Environment($loader);
    }

    private function LoadUser() {
        if ($this->userLoaded == true)
            return;
        $this->userLoaded = true;

        $this->db = new DB;
        $this->user = new User($this->db);
        $this->user->TryVerify();
    }
}
