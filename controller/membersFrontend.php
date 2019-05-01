<?php
require_once ('model/MemberManager.php');

function registerForm()
{
    require ('view/frontend/members/memberRegisterView.php');
}
function addMember($pseudo, $pass, $pass2, $email)
{
    if ($pass === $pass2){
        $hashPass=password_hash($pass, PASSWORD_DEFAULT);
        $memberManager = new \Smilf\Site\Model\MemberManager();
        $test = $memberManager->testMember($pseudo, $hashPass, $email);
        while ($present = $test->fetch()){
            if ($pseudo === $present['pseudo']){
                throw new Exception ('Pseudo déjà pris !<br /><a href="index.php?action=registerForm">Retour au formulaire</a>');
            }elseif ($email === $present['email']){
                throw new Exception ('Adresse e-mail déjà utilisée !<br /><a href="index.php?action=registerForm">Retour au formulaire</a>');
            }
        }
    }else{
        throw new Exception ('Les mots de passe saisis sont différents !<br /><a href="index.php?action=registerForm">Retour au formulaire</a>');
    }
    $test->closeCursor();
    $pseudo=htmlspecialchars($pseudo);
    if (preg_match('#^[a-z\d._-]+@[a-z\d._-]{2,}\.[a-z]{2,4}$#',$email)){
        $memberManager = new \Smilf\Site\Model\MemberManager();
        $affectedLines = $memberManager->addMember($pseudo, $hashPass, $email);
        if ($affectedLines === false){
            throw new Exception ('Le processus d\'inscription a échoué ...<br /><a href="index.php?action=registerForm">Retour au formulaire</a>');
        }
    }else{
        throw new Exception ('Format d\'e-mail non valide !<br /><a href="index.php?action=registerForm">Retour au formulaire</a>');
    }
    header('location:index.php');
}
function connectForm()
{
    require ('view/frontend/members/memberConnectView.php');
}
function memberConnect($pseudo, $pass, $view)
{
    $pseudo=htmlspecialchars($pseudo);
    $pass=htmlspecialchars($pass);
    $view=htmlspecialchars($view);

    $getMember = new \Smilf\Site\Model\MemberManager();
    $member = $getMember->getMember($pseudo);
    
    if (isset($_COOKIE['pass'])){
        if ($_COOKIE['pass'] === $member['pass']){
            $correctPass = true;
        }
    }else{
        $correctPass = password_verify($pass, $member['pass']);
    }

    if (!$getMember){
        throw new Exception ('Mauvais identifiant ou mot de passe ! <br /><a href="index.php?action=connectForm">Retour au formulaire</a>');
    }
    if ($correctPass){
        $_SESSION['memberId'] = $member['ID'];
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['group'] = $member['idGroup'];
        if (isset($_POST['remember']) OR isset($_COOKIE['pseudo'])){
            setcookie('pseudo', $pseudo, time() + 365*24*3600, null, null, false, true);
            setcookie('pass', $member['pass'], time() + 365*24*3600, null, null, false, true);
        }
        header ('location:index.php?action='.$view);
    }else{
        throw new Exception ('Mauvais identifiant ou mot de passe ! <br /><a href="index.php?action=connectForm">Retour au formulaire</a>');
    }
}
function killSession()
{
    $_SESSION = array();
    session_destroy();
    setcookie('pseudo','');
    setcookie('pass','');
}
function checkConnect($view)
{
    if (!isset ($_SESSION['pseudo'])){
        if (isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])){
            header('location:index.php?action=memberConnect&view='.$view);
            exit;
        }else{
            throw new Exception ('Espace réservé aux membres !<br /><a href="index.php">Connectez-vous ici</a>');
        }
    }
}