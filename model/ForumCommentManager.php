<?php
namespace Smilf\Site\Model;
require_once('Manager.php');
class ForumCommentManager extends Manager
{
    public function countComments($idPost)
    {
        $db = $this->dbConnect();
        $req=$db->prepare('SELECT COUNT(*) AS nb FROM comments WHERE idPost = ?');
        $req->bindValue(1, $idPost, \PDO::PARAM_INT);
        $req->execute();
        $nbComs=$req->fetch();
        return $nbComs;
    }
    public function getComments($idPost, $startPage)
    {
        $db = $this->dbConnect();
        $coms = $db->prepare('SELECT ID,author,comment,DATE_FORMAT(dateCom, "%d/%m/%Y à %H h %i") AS dateCom, tStamp FROM comments WHERE idPost= :idPost LIMIT :startPage ,10');
        $coms->bindValue(':idPost', $idPost, \PDO::PARAM_INT);
        $coms->bindValue(':startPage', $startPage, \PDO::PARAM_INT);
        $coms->execute();
        return $coms;
    }
    public function addCom($pseudo, $comment, $idPost, $tStamp)
    {
        $db = $this->dbConnect();
        $addCom=$db->prepare('INSERT INTO comments (author, comment, idPost, dateCom, tStamp) VALUE (:author, :comment, :idPost, CURRENT_TIMESTAMP, :tStamp)');
        $addCom->bindValue(':author', $pseudo, \PDO::PARAM_STR);
        $addCom->bindValue(':comment', $comment, \PDO::PARAM_STR);
        $addCom->bindValue(':idPost', $idPost, \PDO::PARAM_INT);
        $addCom->bindValue(':tStamp', $tStamp, \PDO::PARAM_INT);
        $addCom->execute();
        return $addCom;
    }
    public function getNewComId($idPost, $author, $tStamp)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ID FROM comments WHERE idPost = :idPost AND author = :author AND tStamp = :tStamp');
        $req ->bindValue(':idPost', $idPost, \PDO::PARAM_INT);
        $req->bindValue(':author', $author, \PDO::PARAM_STR);
        $req->bindValue(':tStamp', $tStamp, \PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return $data;
    }
    public function getComment($msgId)
    {
        $db = $this->dbConnect();
        $msg = $db->prepare('SELECT ID, author, comment, DATE_FORMAT(dateCom, "%d/%m/%Y à %H h %i") AS dateCom FROM comments WHERE ID = ?');
        $msg->execute(array($msgId));
        $oldComment=$msg->fetch();
        return $oldComment;
    }
    public function editCom($msgId, $comment)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('UPDATE comments SET comment = ?, tStamp = CURRENT_TIMESTAMP WHERE ID = ?');
        $affectedLines = $comments->execute(array($comment, $msgId));
        return $affectedLines;
    }
    public function countReadComments($idPost, $lastVisit)
    {
        $db = $this->dbConnect();
        $req=$db->prepare('SELECT COUNT(*) AS nb FROM comments WHERE idPost = :idPost AND tStamp < :lastVisit');
        $req->bindValue(':idPost', $idPost, \PDO::PARAM_INT);
        $req->bindValue(':lastVisit', $lastVisit, \PDO::PARAM_INT);
        $req->execute();
        $nbRead=$req->fetch();
        return $nbRead;
    }
}