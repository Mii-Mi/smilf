<?php
session_start();
require ('controller/welcomeFrontend.php');
require ('controller/forumFrontend.php');
require ('controller/membersFrontend.php');
require ('controller/torrentFrontend.php');
try
{
    if (isset($_GET['action'])){
        switch (htmlspecialchars($_GET['action']))
        {
            case 'welcome':
                welcome();
            break;
            case 'forumList':
                listPosts();
            break;
            case 'markRead':
                markRead();
                header('location:index.php?action=forumList');
            break;
            case 'getPost':
                if (isset($_GET['post'])){
                    getPost($_GET['post']);
                }
                else{
                    throw new Exception ('Aucun billet selectionné !<br /><a href="index.php?action=forumList">Retour à la liste</a>');
                    exit;
                }
            break;
            case 'addPost':
                if (empty($_POST['pseudo'])){
                    $_POST['pseudo'] = 'Anosmylfous';
                }
                if (empty($_POST['message'])){
                    header('location: index.php?action=forumList');
                    exit;
                }
                elseif (empty($_POST['title'])){
                    throw new Exception ('Pas de titre !<br /><a href="index.php?action=forumList">Retour à la liste</a>');
                }
                else{
                    addPost($_POST['pseudo'], $_POST['title'], $_POST['message'], time());
                }
            break;
            case 'addCom':
                if (!empty($_POST['post'])){
                    if (empty($_POST['pseudo'])){
                        $_POST['pseudo']='Anosmylfous';
                    }
                    if (empty($_POST['comment'])){
                        header('location:index.php?action=getPost&post='.$_POST['post']);
                        exit;
                    }
                    addCom($_POST['pseudo'], $_POST['comment'], $_POST['post'], time());
                }
            break;
            case 'editForm':
                checkConnect('editForm');
                if (isset($_GET['post']) && isset($_GET['com'])){
                    changeCom($_GET['com']);
                }else{
                    throw new Exception ('le processus d\'édition a échoué : il manque des informations.'); 
                }
            break;
            case 'editCom':
                if (isset($_GET['post']) && isset($_GET['com']) && !empty($_POST['comment'])){
                    editCom($_GET['com'], $_POST['comment'], $_GET['post']);
                }else{
                    throw new Exception ('le processus d\'édition a échoué : il manque des informations.'); 
                }
            break;
            case 'registerForm':
                registerForm();
            break;
            case 'addMember':
                if (!empty($_POST['pseudo']) && !empty($_POST['pass']) && !empty($_POST['pass2']) && !empty($_POST['email'])){
                    addMember($_POST['pseudo'], $_POST['pass'], $_POST['pass2'], $_POST['email']);
                }else{
                    throw new Exception ('Le processus d\'inscription a échoué ... il manque des informations !<br /><a href="index.php?action=registerForm">Retour au formulaire</a>');
                }
            break;
            case 'connectForm':
                connectForm();
            break;
            case 'memberConnect':
                if (!isset($_SESSION['pseudo']) && !isset($_SESSION['pass'])){
                    unset($_SESSION['lastVisit']);
                    if (!empty($_POST['pseudo']) && !empty($_POST['pass'])){
                        memberConnect($_POST['pseudo'], $_POST['pass'], 'welcome');
                    }elseif (isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])){
                        if (isset($_GET['view'])){
                            memberConnect($_COOKIE['pseudo'], $_COOKIE['pass'], $_GET['view']);
                        }else{
                            memberConnect($_COOKIE['pseudo'], $_COOKIE['pass'], 'welcome');
                        }
                    }else{
                        throw new Exception ('Le processus de connexion a échoué ... il manque des informations !<br /><a href="index.php?action=connectForm">Retour au formulaire</a>');
                    }
                }
            break;
            case 'killSession':
                if (isset($_SESSION['pseudo'])){
                    killSession();
                    header ('location:index.php?action=welcome');
                }else{
                    throw new Exception ('vous n\'êtes pas connecté !<br /><a href="index.php?action=welcome">Retour à l\'accueil</a>');
                }
            break;
            case 'share':
                checkConnect('share');
                listTorrents();
            break;
            case 'upTorrent':
                checkConnect('upTorrent');
                if (!empty ($_POST['idCat'])){
                    $_SESSION['form'] = empty($_SESSION['form']);
                    upTorrent($_POST['idCat']);
                }elseif (!empty ($_SESSION['form']['idSubCat'])){
                    upTorrent($_SESSION['form']['idSubCat']);
                }else{
                    throw new Exception ('Pas de catégorie sélectionnée.<br /><a href=index.php?action=share>Retour à la liste</a>');
                }
            break;
            case 'addTorrent':
                checkConnect('addTorrent');
                $_SESSION['form'] = $_POST;
                if (isset($_FILES['torrent']) && $_FILES['torrent']['error'] == 0){
                    if (isset($_FILES['nfo']) && $_FILES['nfo']['error'] == 0){
                        if (!isset($_POST['season'])){
                            $_POST['season']="";
                        }
                        if (!isset($_POST['ep'])){
                            $_POST['ep'] = "";
                        }
                        if (!isset($_POST['status'])){
                            $_POST['status'] = "";
                        }
                        if (!empty ($_POST['title']) && !empty($_POST['idSubCat']) && !empty($_POST['image']) && !empty($_POST['description']) && !empty($_POST['weight']) && !empty($_POST['unit'])){
                            addTorrent($_FILES['torrent'], $_FILES['nfo'], $_POST['idSubCat'], $_POST['title'], $_POST['image'], $_POST['description'], $_POST['free'], $_POST['format'], $_POST['quality'], $_POST['weight'],$_POST['unit'], $_POST['vCodec'], $_POST['vBitrate'], $_POST['language'], $_POST['aCodec'], $_POST['aBitrate'], $_POST['season'], $_POST['ep'], $_POST['status']);
                        }else{
                            throw new Exception ('Il manque des informations d\'upload.');
                        }
                    }else{
                        throw new Exception ('Le fichier NFO n\'a pas pu être chargé.');
                    }
                }else{
                    throw new Exception ('Le fichier torrent n\'a pas pu être chargé.');
                }
            break;
            case 'getTorrent':
                checkConnect('getTorrent');
                if (isset($_GET['torrent'])){
                    getTorrent((int)$_GET['torrent']);
                }else{
                    throw new Exception ('Aucun torrent selectionné !<br /><a href="index.php?action=share">Retour à la liste</a>');
                    exit;
                }
            break;
            case 'addTorCom':
                checkConnect('addTorCom');
                if (!empty($_POST['torrent'])){
                    if (!empty($_POST['pseudo'])){
                        if (empty($_POST['comment'])){
                            header('location:index.php?action=getTorrent&torrent='.$_POST['torrent']);
                            exit;
                        }
                        addTorCom($_POST['pseudo'], $_POST['comment'], $_POST['torrent']);
                    }else{
                        throw new exception ('Connexion perdue !<br /><a href="index.php">Reconnectez-vous svp</a>');
                    }
                }else{
                    throw new Exception ('Vous n\'avez pas envoyé de fichier torrent.');
                }
        break;
            default:
                welcome();
        }
    }
    else{
        welcome();
    }
}
catch (Exception $e){
    $errorMsg = $e->getMessage();
    require ('view/errorView.php');
}