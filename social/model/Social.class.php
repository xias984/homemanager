<?php
require("./system/connection.php");

class Social {
    public function __construct() {
        $this->table = 'social';
    }

    public function createPost($post, $iduser, $date, $reply, $idreply) {
        global $conn;

        $query = "INSERT INTO $this->table (post, iduser, data, reply, idreply) VALUES (
            '$post', '$iduser', '$date', '$reply', $idreply
        );";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            return true;
        }
    }

    public function getAllNewPost() {
        global $conn;

        $query = "SELECT id, post, iduser, data, reply, idreply FROM $this->table WHERE reply = 'N' ORDER BY data DESC";
        $result = mysqli_query($conn, $query);

        $posts = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $posts[] = $row;
        }

        return $posts;
    }

    public function getNextPostId() {
        global $conn;
    
        $query = "SELECT MAX(idreply) FROM $this->table";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $maxId = $row[0];
            
            $nextId = $maxId + 1;
            return $nextId;
        } else {
            return 1;
        }
    }

    public function getPostFromIdForReplies($idpost) {
        global $conn;
        
        $query = "SELECT id, post, iduser, data, reply, idreply FROM $this->table WHERE reply = 'R' AND idreply = $idpost";
        $result = mysqli_query($conn, $query);
        
        $reply = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $reply[] = $row;
        }

        return $reply;
    }

    public function deleteById($idPost, $isReply = false) {
        global $conn;

        $field = $isReply ? 'id' : 'idreply';
        $query = "DELETE FROM {$this->table} WHERE $field = $idPost";
        
        return mysqli_query($conn, $query);
    } 
}
?>