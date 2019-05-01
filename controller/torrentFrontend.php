<?php
require_once('model/TorrentManager.php');
require_once('model/MemberManager.php');
require_once('model/TorrentComManager.php');

function listTorrents()
{
    require ('pagin.php');
    $dataPagin=pagin('torrents');
    $startPage=$dataPagin[0];
    $page = $dataPagin[1];
    $nbDePages=$dataPagin[2];
    $action=$dataPagin[3];

    $torrentManager = new \Smilf\Site\Model\TorrentManager();
    $torrents = $torrentManager->getTorrents($startPage);

    require ('view/frontend/torrents/torrentListView/torrentListView.php');
}
function upTorrent($catForm)
{
    if (!isset ($_SESSION['pseudo']) && isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])){
        header('location:index.php?action=memberConnect&view=upTorrent');
        exit;
    }
    $catForm = (int)$catForm;

    if ($catForm > 0 && $catForm <= 10){
        $upForm = 'view/frontend/torrents/upTorrentView/formUpVideoView.php';
        $special = "";
        switch ($catForm){
            case 1:
                $subcat = 'Animation';
            break;
            case 2:
                $subcat = 'Animation Série';
                ob_start()
                ?>
                <h5>La série</h5>
                <p><label for="season">Nombre de saisons : </label><input type="number" name="season" id="season" value="<?= $_SESSION['form']['season'] ?>" min="1" required />
                <p><label for="ep">Nombre d'épisodes par saison : </label><input type="number" name="ep" id="ep" value="<?= $_SESSION['form']['ep'] ?>" min="1" required /></p>
                <p>Statut : 
                <input type="radio" name="status" value="en cours" id="current"><label for="current">En cours</label>
                <input type="radio" name="status" value="terminée" id="achieved" checked><label for="achieved">Terminée</label>
                <?php
                $special=ob_get_clean();
            break;
            case 3:
                $subcat = 'Concert';
            break;
            case 4:
                $subcat = 'Documentaire';
            break;
            case 5:
                $subcat = 'Émission TV';
            break;
            case 6:
                $subcat = 'Film';
            break;
            case 7:
                $subcat = 'Série TV';
                ob_start()
                ?>
                <h5>La série</h5>
                <p><label for="season">Nombre de saisons : </label><input type="number" name="season" id="season" value="<?= $_SESSION['form']['season'] ?>" min="1" required />
                <p><label for="ep">Nombre d'épisodes par saison : </label><input type="number" name="ep" id="ep" value="<?= $_SESSION['form']['ep'] ?>" min="1" required /></p>
                <p>Statut : 
                <input type="radio" name="status" value="en cours" id="current"><label for="current">En cours</label>
                <input type="radio" name="status" value="terminée" id="achieved" checked><label for="achieved">Terminée</label>
                <?php
                $special=ob_get_clean();
            break;
            case 8:
                $subcat = 'Spectacle';
            break;
            case 9:
                $subcat = 'Sport';
            break;
            case 10:
                $subcat = 'Videoclip';
            break;
            default:
            throw new Exception ('Categorie non valide.<br /><a href=index.php?action=share>Retour à la liste</a>');
        }
    }elseif ($catForm > 10 && $catForm <= 15){
        $upForm = 'view/frontend/torrents/upTorrentView/formUpAudioView.php';
        $special = "";
        switch ($catForm){
            case 11:
                $subcat = 'Karaoke';
            break;
            case 12:
                $subcat = 'Musique';
            break;
            case 13:
                $subcat = 'Podcast radio';
            break;
            case 14:
                $subcat = 'Sample';
            break;
            case 15:
                $subcat = 'Livre audio';
            break;
        }
    }elseif ($catForm > 15 && $catForm <= 20){
        $upForm = 'view/frontend/torrents/upTorrentView/formUpBookView.php';
        $special ="";

    }else{
        throw new Exception ('Categorie en cours de développement.<br /><a href=index.php?action=share>Retour à la liste</a>');
    }
    require ('view/frontend/torrents/upTorrentView/upTorrentView.php');
}
function addTorrent($torrent, $nfo, $idSubCat, $title, $image, $description, $free, $format, $quality, $weight, $unit, $vCodec, $vBitrate, $language, $aCodec, $aBitrate, $season, $ep, $status)
{
    if ($torrent['size'] <= 5000000){
        $fileInfo=pathinfo($torrent['name']);
        $uploadExtension = $fileInfo['extension'];
        $authorizedExtension = 'torrent';
        if ($uploadExtension == $authorizedExtension){
            $hash = exec('transmission-show '.$torrent['tmp_name'].'|grep Hash|cut -c 9-');
            $torrentTkUrl = exec('transmission-show '.$torrent['tmp_name'].'|grep http|cut -c 3-');
            if (preg_match('#^http://tk\.smilf\.cf:6969/announce/?$#', $torrentTkUrl)){
                if ($nfo['size'] <= 300000){
                    $fileInfo = pathinfo($nfo['name']);
                    $uploadExtension = $fileInfo['extension'];
                    $authorizedExtension = 'nfo';
                    if ($uploadExtension == $authorizedExtension){
                        $title = htmlspecialchars($title);
                        $description = htmlspecialchars($description);
                        $free = htmlspecialchars($free);
                        $format = htmlspecialchars($format);
                        $quality = htmlspecialchars($quality);
                        $weight = (int)htmlspecialchars($weight);
                        $unit = htmlspecialchars($unit);
                        $vCodec = htmlspecialchars($vCodec);
                        $vBitrate = (int)htmlspecialchars($vBitrate);
                        $language = htmlspecialchars($language);
                        $aCodec = htmlspecialchars($aCodec);
                        $aBitrate = (int)htmlspecialchars($aBitrate);
                        $season = (int)htmlspecialchars($season);
                        $ep = (int)htmlspecialchars($ep);
                        $status = htmlspecialchars($status);
                        $torrent['name'] = htmlspecialchars($torrent['name']);
                        $nfo['name'] = htmlspecialchars($nfo['name']);
                        if (preg_match('#^https?://((w{3}|w{2})\.)?[a-z_.\d-]+\.([a-z]{2,4})\S*\.(jpg|png)$#i', $image)){
                            $getMember = new \Smilf\Site\Model\MemberManager();
                            $member = $getMember->getMember($_SESSION['pseudo']);
                            $uplaoderId = $member['ID'];

                            $torrentManager = new \Smilf\Site\Model\TorrentManager();
                            $affectedLines = $torrentManager->addTorrent($torrent['name'], $hash, $title, $uplaoderId, $image, $description, $free, $format, $quality, $weight, $unit, $vCodec, $vBitrate, $language, $aCodec, $aBitrate, $season, $ep, $status, $idSubCat, $nfo['name']);
                            if ($affectedLines == false){
                                throw new Exception ('Le processus d\'upload a échoué.<br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
                            }else{
                                move_uploaded_file($torrent['tmp_name'], 'public/torrents/'.basename($torrent['name']));
                                move_uploaded_file($nfo['tmp_name'], 'public/torrents/nfo/'.basename($nfo['name']));
                                header('location:index.php?action=share');
                            }
                        }else{
                            throw new Exception ('Adresse de l\'image non valide.<br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
                        }
                    }else{
                        throw new Exception ('Fichier NFO non valide.<br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
                    }
                }else{
                    throw new Exception ('Fichier NFO trop volumineux. Taille maxi : 300 ko<br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
                }
            }else{
                throw new Exception ('Adresse tracker invalide dans le fichier torrent.<br />L\'adresse correcte est http://tk.smilf.cf:6969/announce</br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
            }
        }else{
            throw new Exception ('Fichier torrent non valide.<br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
        }
    }else{
        throw new Exception ('Fichier torrent trop volumineux. Taille maxi : 5 Mo.<br /><a href="index.php?action=upTorrent">Retour au formulaire</a>');
    }
}
function getTorrent($idTorrent)
{
    $torrentManager = new \Smilf\Site\Model\TorrentManager();
    $torrent = $torrentManager->getTorrent($idTorrent);

    if (empty($torrent)){
        throw new Exception ('Vous demandez un torrent qui n\'existe pas !<br /><a href="index.php?action=share">Retour à la liste</a>');
        exit;
    }

    if ($torrent['torIdSubCat'] > 0 && $torrent['torIdSubCat'] <= 10){
        $catView = 'torrentVideoView.php';
    }elseif ($torrent['torIdSubCat'] > 10 && $torrent['torIdSubCat'] <= 15){
        $catView = 'torrentAudioView.php';
    }

    $commentManager = new \Smilf\Site\Model\TorrentComManager();
    $coms = $commentManager->getTorComs($idTorrent);
    
    require ('view/frontend/torrents/torrentView/torrentView.php');
}
function addTorCom($pseudo, $comment, $idTorrent)
{
    $pseudo = strip_tags($pseudo);
    $comment = strip_tags($comment);

    $addTorCom =new \Smilf\Site\Model\TorrentcomManager();
    $affectedLines = $addTorCom->addTorCom($pseudo, $comment, $idTorrent);

    if ($affectedLines === false){
        throw new Exception ('Votre Commentaire n\'a pas pu être envoyé');
    }
    else{
        header('location:index.php?action=getTorrent&torrent='.$idTorrent);
    }
}