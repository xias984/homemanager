<?php
require("./social/model/Social.class.php");
require("./auth/model/Auth.class.php");

class SocialController
{
    public function __construct() {
        $this->social = new Social();
        $this->iduser = $_SESSION['iduser'];
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function insertPost($post, $idreply = null) {
        $response = false;
        $replyType = !empty($idreply) ? 'R' : 'N';
    
        if ($replyType === 'N') {
            $idreply = $this->social->getNextPostId();
        }
    
        if ($this->social->createPost($post, $this->iduser, $this->datetime, $replyType, $idreply)) {
            $response = true;
        }
        
        if ($response) {
            header("Location: " . refreshPageWOmsg() . "&idmsg=" . ($replyType === 'R' ? 20 : 18));
        } else {
            header("Location: " . refreshPageWOmsg() . "&idmsg=" . ($replyType === 'R' ? 21 : 19));
        }
    }
    
    public function selectData($data) {
        $dataArray = array();
    
        if ($data) {
            foreach ($data as $item) {
                $userData = new Auth();
                $userInfo = $userData->getInfoUserById($item['iduser']);
                $dataItem = array();
    
                if (!empty($userInfo)) {
                    $dataItem['username'] = $userInfo['firstname'] . ' ' . $userInfo['familyname'];
                    $dataItem['iduser'] = $item['iduser'];
                } else {
                    $dataItem['username'] = 'Utente cancellato';
                    $dataItem['iduser'] = 0;
                }
    
                $dataItem['data'] = date('d/m/Y H:i', strtotime($item['data']));
                $dataItem['post'] = $item['post'];
                $dataItem['id'] = $item['id'];
                $dataItem['idreply'] = $item['idreply'];
    
                $dataArray[] = $dataItem;
            }
        }
        
        return $dataArray;
    }
    
    public function selectPosts() {
        $postData = $this->social->getAllNewPost();
        return $this->selectData($postData);
    }
    
    public function selectReplies($idreply) {
        $replies = $this->social->getPostFromIdForReplies($idreply);
        return $this->selectData($replies);
    }

    public function deletePost($postData) {
        $idToDelete = null;
        $idmsg = null;
        $idReply = false;
    
        if (!empty($postData['deletereply'])) {
            $idToDelete = $postData['deletereply'];
        } elseif (!empty($postData['deletepost'])) {
            $idToDelete = $postData['deletepost'];
        }
    
        if ($idToDelete !== null) {
            if ($this->social->deleteById($idToDelete, $idReply)) {
            }
        }
    }       
}