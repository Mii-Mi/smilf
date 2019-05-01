<?php
namespace Smilf\Site\Model;
require_once('Manager.php');
class MemberManager extends Manager
{
    public function testMember($pseudo, $hashPass, $email)
    {
        $db = $this->dbConnect();
        $search = $db->prepare('SELECT * FROM  members WHERE pseudo=:pseudo OR pass=:hashpass OR email=:email');
        $search -> bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        $search -> bindValue(':hashpass', $hashPass, \PDO::PARAM_STR);
        $search -> bindValue(':email', $email, \PDO::PARAM_STR);
        $search -> execute();

        return $search;
    }
    public function addMember($pseudo, $hashPass, $email)
    {
        $db = $this->dbConnect();
        $newMember = $db->prepare('INSERT INTO members VALUES (null,:pseudo, :pass, :email, CURRENT_TIMESTAMP, 3, 0)');
        $newMember->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        $newMember->bindValue(':pass', $hashPass, \PDO::PARAM_STR);
        $newMember->bindValue(':email', $email, \PDO::PARAM_STR);
        $newMember->execute();

        return $newMember;
    }
    public function getMember($pseudo)
    {
        $db = $this->dbConnect();
        $member = $db->prepare('SELECT * FROM members WHERE pseudo = ?');
        $member -> execute(array($pseudo));
        $getMember = $member->fetch();
        return $getMember;
    }
    public function setLastVisit($tStamp, $idMember)
    {
        $db = $this->dbConnect();
        $lastVisit = $db->prepare('UPDATE members SET lastVisit = :tStamp WHERE ID = :idMember');
        $lastVisit->bindValue(':tStamp', $tStamp, \PDO::PARAM_INT);
        $lastVisit->bindValue(':idMember', $idMember, \PDO::PARAM_INT);
        $lastVisit->execute();
        return $lastVisit;
    }
    
}