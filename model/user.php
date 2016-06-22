<?php

class User {

    private $db = null;

    // Static salt for session tokens
    // Less secure but allows simple lookup
    // in DB rather than rehashing a bunch of
    // random salts.
    private $sesSalt;

    private $info = null;
    private $loggedIn = false;

    public function __construct($database) {
        include('/var/www/pw.php');
        $this->sesSalt = $pass['sesSalt'];
        $this->db = $database;
    }

    public function GetInfo() {
        return $this->info;
    }

    public function GetName() {
        if (!$this->loggedIn)
            return null;

        return $this->info['name']; 
    }

    public function LoggedIn() {
        return $this->loggedIn;
    }

    public function Login($name, $pass, $stay) {
        $usr = $this->db->GetPassword($name);
        if ($usr === false)
            return false;

        $res = password_verify($pass, $usr['hash']);
        if ($res == false)
            return false;

        $this->NewSession(intval($usr['id']), intval($stay));
        return true;
    }

    public function Logout() {
        if (!isset($_COOKIE['session']))
            return false;

        $token = $_COOKIE['session'];
        $hash = $this->HashToken($token);
        $this->db->ExpireSession($hash);
        setcookie('session',"",time() - 3600,'/',null,true,true);
        $this->loggedIn = false;
        $this->info = null;
        return true;
    }

    // Attempt to log user in from cookies
    public function TryVerify() {
        if (!isset($_COOKIE['session']))
            return false;

        $token = $_COOKIE['session'];
        $hash = $this->HashToken($token);
        $us = $this->db->GetSession($hash);
        if ($us === false)
            return false;
        $this->info = $us[0];
        $this->loggedIn = true;
        return true;
    }

    public function NewSession($userid, $stay) {
        $token = $this->token();
        $hash = $this->HashToken($token);
        $time = 0;

        if ($stay == true)
            $time = time() + 86400 * 365;

        setcookie('session',$token,$time,'/',null,true,true);
        $this->db->NewSession($userid, $hash, $stay);
    }

    public function HashToken($token) {
        return crypt($token, $this->sesSalt); 
    }

    public function Token() {
        $str = "";
        for ($i = 0; $i < 32; $i++) {
            $str .= $this->base64(ord(openssl_random_pseudo_bytes(1)) / 4);
        }
        return $str;
    }

    public function base64($num) {
        $lot = 'abcdefghijklmnopqrstuvwxyxABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
        return $lot[intval($num)];
    }

}
