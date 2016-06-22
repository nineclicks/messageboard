<?php

class DB {

    private $db;


    public function __construct() {
        include('/var/www/pw.php');
        $this->db = new PDO('mysql:host=localhost;dbname=board',
            'board',$pass['sql']
        );
    }

    public function __destruct() {
        $this->db = null;
    }

    public function ExpireSession($hash) {
        $query = 'UPDATE session SET expiration = UNIX_TIMESTAMP() ' . 
            'WHERE hash = ?';
        $this->ex($query, $hash);
    }

    public function GetPassword($name) {
        $query = 'SELECT p.hash, u.id FROM password p INNER JOIN user u '. 
            'ON p.id = u.id WHERE u.name = ? LIMIT 1';
        $res = $this->ex($query, $name);
        if (count($res) == 0)
            return false;
        return $res[0];
    }

    public function GetSession($id) {
        $res = $this->ex('CALL verifytoken(?)', $id);
        if (count($res) == 0)
            return false;
        return $res;        
    }

    public function NewSession($userid, $hash, $stay) {
        $query = 'INSERT INTO session(hash, expiration, stay, userid) ' . 
            'VALUES(?,?,?,?)';
        $time;
        if ($stay)
            $time = time() * 86400 * 365;
        else
            $time = time() + 3600;

        $arr = array($hash, $time, $stay, $userid);
        $this->ex($query, $arr);
    }

    public function ex($query, $arr = null) {
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            error_log("prepare error in {$caller['file']} line {$caller['line']}");
            error_log("query: " . $query);
            error_log($this->db->errorInfo()[2]);
            return false;
        }

        if (!is_null($arr) && !is_array($arr))
            $arr = array($arr);

        $stmt->execute($arr);
        $res = $stmt->fetchAll();
        return $res;
    }

}
