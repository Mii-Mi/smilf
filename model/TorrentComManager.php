<?php
namespace Smilf\Site\Model;
require_once('Manager.php');
class TorrentComManager extends Manager
{
    public function countTorComments($idTorrent)
    {
        $db = $this->dbConnect();
        $req=$db->prepare('SELECT COUNT(*) AS nb FROM torrentsComs WHERE idTorrent = ?');
        $req->bindValue(1, $idTorrent, \PDO::PARAM_INT);
        $req->execute();
        $nbComs=$req->fetch();
        return $nbComs;
    }
    public function getTorComs($idTorrent)
    {
        $db = $this->dbConnect();
        $coms = $db->prepare('SELECT id,author,comment,DATE_FORMAT(dateCom, "%d/%m/%Y à %H h %i") AS dateCom FROM torrentsComs WHERE idTorrent= :idTorrent ORDER BY id DESC');
        $coms->bindValue(':idTorrent', $idTorrent, \PDO::PARAM_INT);
        $coms->execute();
        return $coms;
    }
    public function addTorCom($pseudo, $comment, $idTorrent)
    {
        $db = $this->dbConnect();
        $addCom=$db->prepare('INSERT INTO torrentsComs (author, comment, idTorrent) VALUE (:author, :comment,:idTorrent)');
        $addCom->bindValue(':author', $pseudo, \PDO::PARAM_STR);
        $addCom->bindValue(':comment', $comment, \PDO::PARAM_STR);
        $addCom->bindValue(':idTorrent', $idTorrent, \PDO::PARAM_INT);
        $addCom->execute();
        return $addCom;
    }
}