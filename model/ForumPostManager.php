<?php
namespace Smilf\Site\Model;
require_once('Manager.php');
class ForumPostManager extends Manager
{
    public function getPosts($startPage)
    {
        $db = $this->dbConnect();
        $posts = $db->prepare('SELECT ID,pseudo,title,message,DATE_FORMAT(dateMsg, "Le %d/%m/%Y à %Hh%i") dateMsg, tStamp FROM posts ORDER BY tStamp DESC LIMIT :startPage , 10');
        $posts->bindValue(':startPage', $startPage, \PDO::PARAM_INT);
        $posts->execute();
        return $posts;
    }
    public function getPost($idPost)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ID,pseudo,title,message,DATE_FORMAT(dateMsg, "%d/%m/%Y à %H h %i") AS dateMsg FROM posts WHERE ID = :id');
        $req->bindValue(':id', $idPost, \PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return $data;
    }
    public function getNewPostId($author, $tStamp)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ID FROM posts WHERE pseudo = :author AND tStamp = :tStamp');
        $req->bindValue(':author', $author, \PDO::PARAM_STR);
        $req->bindValue(':tStamp', $tStamp, \PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return $data;
    }
    public function countPosts()
    {
        $db = $this->dbConnect();
        $req=$db->query('SELECT COUNT(*) AS nb FROM posts');
        $nbPosts=$req->fetch();
        return $nbPosts;
    }
    public function addPost($pseudo, $title, $content, $tStamp)
    {
        $db = $this->dbConnect();
        $newPost = $db->prepare('INSERT INTO posts (pseudo, title, message, dateMsg, tStamp) VALUES (:pseudo, :title, :content, CURRENT_TIMESTAMP, :tStamp)');
        $affectedLines = $newPost->execute(array(':pseudo'=>$pseudo, ':title'=>$title, ':content'=>$content, ':tStamp'=>$tStamp));
        return $affectedLines;
    }
    public function setTstampPost($tStamp, $idPost)
    {
        $db = $this->dbConnect($tStamp, $idPost);
        $newTime = $db->prepare('UPDATE posts SET tStamp = :tStamp WHERE id = :idPost');
        $newTime->bindValue(':tStamp', $tStamp, \PDO::PARAM_INT);
        $newTime->bindValue(':idPost', $idPost, \PDO::PARAM_INT);
        $newTime->execute();
        return $newTime;
    }
}