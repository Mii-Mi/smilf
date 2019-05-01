<?php

require_once('model/WelcomePostManager.php');
require_once('model/MemberManager.php');

function welcomePost()
{
    $postManager = new \Smilf\Site\Model\WelcomePostManager();
    $post = $postManager->getPost();
    return $post;
}
function welcome()
{
    if (!isset ($_SESSION['pseudo']) && isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])){
        header('location:index.php?action=memberConnect&view=welcome');
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
    $post = welcomePost();
    if (isset($_SESSION['pseudo'])){
        $member = $_SESSION['pseudo'];
        $connect = '<a href="index.php?action=killSession">Se déconnecter</a>';
        $shareSpaceLink = '<li><a href="index.php?action=share">Espace de partage</a></li>';
        $register = '';
    }else{
        $member = 'Espace membres';
        $connect = '<a href="index.php?action=connectForm">Se connecter</a>';
        $shareSpaceLink = '';
        $register = '<li><a href="index.php?action=registerForm">S\'inscrire</a></li>';
    }
    require('view/frontend/welcomeView.php');
}