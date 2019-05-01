<?php
namespace Smilf\Site\Model;
require_once('Manager.php');
class TorrentManager extends Manager
{
    public function addTorrent($name, $hash, $title, $uploaderId, $image, $description, $free, $format, $quality, $weight, $unit, $vCodec, $vBitrate, $language, $aCodec, $aBitrate, $season, $ep, $status, $idSubCat, $nfoName)
    {
        $db = $this -> dbConnect();
        $newTorrent = $db->prepare('INSERT INTO torrents VALUES (null, :name, :hash, :title, :uploaderId, :image, :description, :free, :format, :quality, :weight, :unit, :vCodec, :vBitrate, :language, :aCodec, :aBitrate, :season, :ep, :status, CURRENT_TIMESTAMP, :idSubCat, :nfoName)');
        $newTorrent->bindValue(':name', $name, \PDO::PARAM_STR);
        $newTorrent->bindValue(':hash', $hash, \PDO::PARAM_STR);
        $newTorrent->bindValue(':title', $title, \PDO::PARAM_STR);
        $newTorrent->bindValue(':uploaderId', $uploaderId, \PDO::PARAM_INT);
        $newTorrent->bindValue(':image', $image, \PDO::PARAM_STR);
        $newTorrent->bindValue(':description', $description, \PDO::PARAM_STR);
        $newTorrent->bindValue(':free', $free, \PDO::PARAM_STR);
        $newTorrent->bindValue(':format', $format, \PDO::PARAM_STR);
        $newTorrent->bindValue(':quality', $quality, \PDO::PARAM_STR);
        $newTorrent->bindValue(':weight', $weight, \PDO::PARAM_INT);
        $newTorrent->bindValue(':unit', $unit, \PDO::PARAM_STR);
        $newTorrent->bindValue(':vCodec', $vCodec, \PDO::PARAM_STR);
        $newTorrent->bindValue(':vBitrate', $vBitrate, \PDO::PARAM_INT);
        $newTorrent->bindValue(':language', $language, \PDO::PARAM_STR);
        $newTorrent->bindValue(':aCodec', $aCodec, \PDO::PARAM_STR);
        $newTorrent->bindValue(':aBitrate', $aBitrate, \PDO::PARAM_INT);
        $newTorrent->bindValue(':season', $season, \PDO::PARAM_INT);
        $newTorrent->bindValue(':ep', $ep, \PDO::PARAM_INT);
        $newTorrent->bindValue(':status', $status, \PDO::PARAM_STR);
        $newTorrent->bindValue(':idSubCat', $idSubCat, \PDO::PARAM_INT);
        $newTorrent->bindValue(':nfoName', $nfoName, \PDO::PARAM_INT);
        $newTorrent->execute();
        return $newTorrent;
    }
    public function countTor(){
        $db = $this -> dbConnect();
        $req=$db->query('SELECT COUNT(*) AS nb FROM torrents');
        $nbTorrents=$req->fetch();
        return $nbTorrents;
    }
    public function getTorrents($startPage)
    {
        $db = $this->dbConnect();
        $torrents = $db->prepare
            (
            'SELECT m.pseudo memberName, sub.subCategory subCat, t.id torId, t.name torName, t.hash torHash, t.title torTitle, t.uploaderId torUploaderId, t.image torImage, t.description torDescription, t.format torFormat, t.quality torQuality, t.weight torWeight, t.unit torWeightUnit, t.vCodec torVcodec, t.vBitrate torVbitrate, t.language torLanguage, t.aCodec torAcodec, t.aBitrate torAbitrate, t.season torSeason, t.ep torEp, t.status torStatus ,DATE_FORMAT(t.uploadDate, "Le %d/%m/%Y à %Hh%i") torUploadDate, t.idSubCat torIdSubCat, t.nfoName torNfoName 
            FROM torrents t
            INNER JOIN members m
            ON m.ID = t.uploaderId
            INNER JOIN torrentSubGroup sub
            ON sub.id = t.idSubCat
            ORDER BY torId DESC LIMIT :startPage , 10' 
            );
        $torrents->bindValue(':startPage', $startPage, \PDO::PARAM_INT);
        $torrents->execute();
        return $torrents;
    }
    public function getTorrent($idTorrent)
    {
        $db = $this->dbConnect();
        $req = $db->prepare
            (
            'SELECT m.pseudo memberName, sub.subCategory subCat, t.id torId, t.name torName, t.hash torHash, t.title torTitle, t.uploaderId torUploaderId, t.image torImage, t.description torDescription, t.free torFree, t.format torFormat, t.quality torQuality, t.weight torWeight, t.unit torWeightUnit, t.vCodec torVcodec, t.vBitrate torVbitrate, t.language torLanguage, t.aCodec torAcodec, t.aBitrate torAbitrate, t.season torSeason, t.ep torEp, t.status torStatus ,DATE_FORMAT(t.uploadDate, "Le %d/%m/%Y à %Hh%i") torUploadDate, t.idSubCat torIdSubCat, t.nfoName torNfoName 
            FROM torrents t
            INNER JOIN members m
            ON m.ID = t.uploaderId
            INNER JOIN torrentSubGroup sub
            ON sub.id = t.idSubCat
            WHERE t.id = :idTorrent'
            );
        $req->bindValue(':idTorrent', $idTorrent, \PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch();
        return $data;
    }
}