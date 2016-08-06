<?php

class DB {

    private $db;


    public function __construct() {
        include('/var/www/pw.php');
        $this->db = new PDO('mysql:host=localhost;dbname=board',
            'board',$pass['sql']
        );

        // Keep INTs as INT when bound to statements
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function __destruct() {
        $this->db = null;
    }

    public function Log($message) {
        $query = 'INSERT INTO log(date,message) VALUES(?,?)';
        $params = array(time(), $message);
        $this->ex($query, $params);
    }

    public function ApprovePost($id) {
        $query = 'UPDATE post SET status = 1 WHERE code = ?';
        $this->ex($query, $id);
    }

    public function DeletePost($id, $user) {
        $userStatus = $user->GetStatus();
        $userID = $user->GetID();
        // Only delete if author is current user or current
        // user has moderation status (>1).
        $query = 'UPDATE post SET status = 2 WHERE code = ? AND (author = ? OR ? > 1)';
        $params = array($id, $userID, $userStatus);
        $this->ex($query, $params);
    }

    public function PostReply($newID, $arr) {
        $query = 'CALL postreply(?,?,?,?,?)';
        $params = array(
            $arr['content'],
            $newID,
            $arr['parent'],
            $arr['date'],
            $arr['name'],
        );
        return $this->ex($query, $params);
    }

    public function GetPost($code, $uid, $mod = 0) {
        $query = 'CALL getposts(?,?,?)';
        $params = array($code, $uid, $mod);
        return $this->ex($query, $params, true);
    }

    public function GetBoards() {
        $query = 'SELECT b.name, d.text from board b LEFT JOIN ' . 
            'boarddesc d ON b.id = d.id';
        return $this->ex($query);
    }

    public function GetBoardPosts($board, $start, $count, $uid) {
        $query = 'SELECT *,(SELECT COUNT(*) FROM post p2 WHERE p2.root = p.id AND (p2.status = 1 OR p2.author = ?)) AS count FROM board b INNER JOIN boardpost bp ON b.id = bp.boardid INNER JOIN post p ON p.id = bp.postid WHERE b.name = ? ORDER BY p.date DESC LIMIT ? OFFSET ?';
        $params = array($uid, $board, $count, $start);
        return $this->ex($query, $params);
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

    public function CreateUser($name, $hash) {
        $res = $this->ex('CALL createuser(?,BINARY ?)', array($name,$hash));
        return $res[0]['id'];
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

    public function ex($query, $arr = null, $group = false) {
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
        $res;
        if ($group)
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
        else
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

}
