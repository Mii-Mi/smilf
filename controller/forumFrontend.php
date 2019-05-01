<?php
require_once ('model/ForumPostManager.php');
require_once ('model/ForumCommentManager.php');

function listPosts()
{
    if (!isset ($_SESSION['pseudo']) && isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])){
        header('location:index.php?action=memberConnect&view=forumList');
        exit;
    }
    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['memberId'])){
        $_SESSION['lastVisit'] = 0;
    }else{
        if (!isset($_SESSION['lastVisit'])){
            $member = new \Smilf\Site\Model\MemberManager();
            $data = $member->getMember($_SESSION['pseudo']);
            $_SESSION['lastVisit'] = $data['lastVisit'];

            $lastVisit = new \Smilf\Site\Model\MemberManager();
            $newVisit = $lastVisit->setLastVisit(time(), $_SESSION['memberId']);

            if ($newVisit === false){
                throw new Exception ('Échec initialisation dernière visite.');
            }
        }else{
            $lastVisit = new \Smilf\Site\Model\MemberManager();
            $newVisit = $lastVisit->setLastVisit(time(), $_SESSION['memberId']);

            if ($newVisit === false){
                throw new Exception ('Échec de la mise à jour dernière visite.');
            }
        }
    }
    require ('pagin.php');
    $dataPagin=pagin('posts');
    $startPage=$dataPagin[0];
    $page = $dataPagin[1];
    $nbDePages=$dataPagin[2];
    $action=$dataPagin[3];

    $read = 'public/images/read.png';
    $unread = 'public/images/unread.png';

    $postManager = new \Smilf\Site\Model\ForumPostManager();
    $posts = $postManager->getPosts($startPage);
    require ('view/frontend/forum/forumListView.php');
}
function getPost($idPost)
{
    if (!isset ($_SESSION['pseudo']) && isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])){
        header('location:index.php?action=memberConnect&view=getPost&post='.$idPost);
        exit;
    }
    require ('pagin.php');
    $dataPagin=pagin('comments', $idPost);
    $startPage=$dataPagin[0];
    $page = $dataPagin[1];
    $nbDePages=$dataPagin[2];
    $action=$dataPagin[3];
    $nbComs=$dataPagin[4];

    $postManager = new \Smilf\Site\Model\ForumPostManager();
    $post = $postManager->getPost($idPost);

    $texte=$post['message'];
    include('public/parser.php');

    if (empty($post)){
        throw new Exception ('Vous demandez un billet qui n\'existe pas !<br /><a href="index.php?action=forumList">Retour à la liste</a>');
        exit;
    }

    $commentManager = new \Smilf\Site\Model\ForumCommentManager();
    $coms = $commentManager->getComments($idPost, $startPage);

    $numMsg = $startPage + 1;

    $_SESSION['read'.$idPost] = time();
    
    require ('view/frontend/forum/forumPostView.php');
}
function addPost($pseudo, $title, $content, $tStamp)
{
    $pseudo=strip_tags($pseudo);
    $content=strip_tags($content);
    $title=strip_tags($title);

    $newPost = new \Smilf\Site\Model\ForumPostManager();
    $affectedLines = $newPost->addPost($pseudo, $title, $content, $tStamp);

    if ($affectedLines === false){
        throw new Exception ('Votre message n\'a pas pu être envoyé !');
    }
    else{
        $newPostId = new \Smilf\Site\Model\ForumPostManager();
        $postId = $newPostId->getNewPostId($pseudo, $tStamp);
        header('location:index.php?action=getPost&post='.$postId['ID']);
    }
}
function addCom($pseudo, $comment, $idPost, $tStamp)
{
    $pseudo = strip_tags($pseudo);
    $comment = strip_tags($comment);

    $addCom =new \Smilf\Site\Model\ForumCommentManager();
    $affectedLines = $addCom->addCom($pseudo, $comment, $idPost, $tStamp);

    if ($affectedLines === false){
        throw new Exception ('Votre Commentaire n\'a pas pu être envoyé');
    }
    else{
        $setTstampPost = new \Smilf\Site\Model\ForumPostManager();
        $newTime = $setTstampPost->setTstampPost($tStamp, $idPost);
        if ($newTime === false){
            throw new Exception ('Problème de mise à jour timestamp');
        }else{
            $newComId = new \Smilf\Site\Model\ForumCommentManager();
            $comId = $newComId->getNewComId($idPost, $pseudo, $tStamp);
            $_SESSION['read'.$idPost] = time();
            header('location:index.php?action=getPost&post='.$idPost.'#'.$comId['ID']);
        }
    }
}
function changeCom($msgId)
{
    $postManager= new \Smilf\Site\Model\ForumPostManager();
    $commentManager = new \Smilf\Site\Model\ForumCommentManager();
    $post=$postManager->getPost(strip_tags((int)$_GET['post']));
    if (empty($post)){
        throw new Exception ('Vous demandez un billet qui n\'existe pas !<br /><a href="index.php?action=forumList">Retour à la liste</a>');
        exit;
    }
    $oldComment = $commentManager->getComment(strip_tags((int)$msgId));
    if (empty($oldComment)){
        throw new Exception ('Vous demandez un commentaire qui n\'existe pas !<br /><a href="index.php?action=forumList">Retour à la liste</a>');
        exit;
    }
    if (isset($_SESSION[$oldComment['ID'].'edit']) && $_SESSION[$oldComment['ID'].'edit'] === true){
        require('view/frontend/forum/editView.php');
    }else{
        throw new Exception ('Vous n\'avez pas la permission d\'accéder au commentaire demandé !');
    }
}
function editCom($msgId, $comment, $postId)
{
    $commentManager = new \Smilf\Site\Model\ForumCommentManager();
    if (isset($_SESSION[$msgId.'edit']) && $_SESSION[$msgId.'edit'] === true){
        $affectedLines = $commentManager->editCom(strip_tags((int)$msgId), strip_tags($comment));
        if ($affectedLines === false){
            throw new Exception ('Le message n\'a pas pu être modifié');
            exit;
        }else{
            $setTstampPost = new \Smilf\Site\Model\ForumPostManager();
            $newTime = $setTstampPost->setTstampPost(time(), strip_tags((int)$postId));
            if ($newTime === false){
                throw new Exception ('Problème de mise à jour timestamp');
            }else{
                $_SESSION['read'.$postId] = time();
                $_SESSION[$msgId.'edit'] === false;
                header ('location:index.php?action=getPost&post='.$postId.'#'.$msgId);
            }
        }
    }else{
        throw new Exception ('Vous n\'avez pas la permission d\'accéder au commentaire demandé !');
        exit;
    }

}
function markRead()
{
    $_SESSION['lastVisit'] = time();
}