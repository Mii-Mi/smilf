<?php
namespace Smilf\Site\Model;
require_once('Manager.php');
class WelcomePostManager extends Manager
{
    public function getPost()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT ID,pseudo, message FROM posts ORDER BY ID DESC LIMIT 0,1');
        $post = $req->fetch();
        return $post;
    }
}