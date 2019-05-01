<?php
require_once ('model/ForumPostManager.php');
require_once ('model/ForumCommentManager.php');
require_once ('model/TorrentManager.php');

function pagin($action, $idPost='')
{
    switch ($action)
    {
        case 'posts':
            $postCountManager = new \Smilf\Site\Model\ForumPostManager();
            $count = $postCountManager->countPosts();
            $action = 'forumList';
        break;
        case 'comments':
            $commentCountManager = new \Smilf\Site\Model\ForumCommentManager();
            $count = $commentCountManager->countComments($idPost);
            $action = 'getPost';
            $CountReadComments = new \Smilf\Site\Model\ForumCommentManager();
            $nbRead = $CountReadComments->countReadComments($idPost, $_SESSION['lastVisit']);
        break;
        case 'torrents':
            $torrentCountManager = new \Smilf\Site\Model\TorrentManager();
            $count = $torrentCountManager->countTor();
            $action ='share';
        break;
        case 'torComs':
            $torComsCountManager = new \Smilf\Site\Model\TorrentComManager();
            $count = $torComsCountManager->countTorComments($idPost);
            $action = 'getTorrent';
        break;
        default:
        throw new Exception ('Requête pagin incomprise');
    }
    
    $totalPosts=$count['nb'];
    $totalPages=ceil($totalPosts / 10);

    if ($totalPages == 0){
        $totalPages = 1;
    }

    if (isset($_GET['page'])){
        $_GET['page']=(int)strip_tags($_GET['page']);
            if ($_GET['page'] >= 1 AND $_GET['page'] <= $totalPages){
                $page=$_GET['page'];
            }
            else{
                $page = 1;
            }
    }
    elseif (isset($_POST['page'])){
        $_POST['page']=(int)strip_tags($_POST['page']);
        if ($_POST['page'] >= 1 AND $_POST['page'] <= $totalPages){
            $page=$_POST['page'];
        }
        else{
            $page = 1;
        }
    }
    else{
        if (isset($nbRead['nb']) && $nbRead['nb'] > 10){
            $page = ceil($nbRead['nb'] / 10);
        }else{
        $page=1;
        }
    }

    $startPage=($page - 1)*10;

    if ($action === 'getPost'){
        $action = $action.'&amp;post='.$idPost;
    }elseif ($action === 'getTorrent'){
        $action = $action.'&amp;torrent='.$idPost;
    }
    return $dataPagin=array($startPage, $page, $totalPages, $action, $totalPosts);
}