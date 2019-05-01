<?php
$title = 'torrents - smilf.cf';
ob_start()
?>
<header>
    <div class="cont1torlist">
        <p class="logo"><a href="index.php"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à l'accueil"/></a></p>
        <form class="formbloc" action="index.php?action=upTorrent" method="post">
            <h3>Uploader un torrent</h3>
            <p><label for="idCat">Catégorie : </label>
            <select class="select" name="idCat" id="idCat">
                <option value="0">--Sélectionner--</option>
                <optgroup label="Vidéo">
                    <option value="1">Animation</option>
                    <option value="2">Animation Série</option>
                    <option value="3">Concert</option>
                    <option value="4">Documentaire</option>
                    <option value="5">Émission TV</option>
                    <option value="6">Film</option>
                    <option value="7">Série TV</option>
                    <option value="8">Spectacle</option>
                    <option value="9">Sport</option>
                    <option value="10">Videoclip</option>
                </optgroup>
                <optgroup label="Audio">
                    <option value="11">Karaoke</option>
                    <option value="12">Musique</option>
                    <option value="13">Podcast radio</option>
                    <option value="14">Sample</option>
                </optgroup>
                <optgroup label="E-book">
                    <option value="15">Audio</option>
                    <option value="16">BD</option>
                    <option value="17">Comics</option>
                    <option value="18">Livre</option>
                    <option value="19">Manga</option>
                    <option value="20">Presse</option>
                </optgroup>
                <optgroup label="Application">
                    <option value="21">Divers</option>
                    <option value="22">Formation</option>
                    <option value="23">Linux</option>
                    <option value="24">Mac Os</option>
                    <option value="25">Smartphone</option>
                    <option value="26">Tablette</option>
                    <option value="27">Windows</option>
                </optgroup>
                <optgroup label="Jeux video">
                    <option value="28">Autre</option>
                    <option value="29">Linux</option>
                    <option value="30">Mac Os</option>
                    <option value="31">Microsoft</option>
                    <option value="32">Nintendo</option>
                    <option value="33">Smartphone</option>
                    <option value="34">Sony</option>
                    <option value="35">Tablette</option>
                    <option value="36">Windows</option>
                </optgroup>
                <optgroup label="Emulation">
                    <option value="37">Emulateur</option>
                    <option value="38">Rom/Iso</option>
                </optgroup>
                <optgroup label="GPS">
                    <option value="39">Application</option>
                    <option value="40">Cartes</option>
                    <option value="41">Divers GPS</option>
                </optgroup>
            </select>
            <input class="submit" type="submit" value="Envoi" /></p>
        </form>
    </div>
    <h1>Espace de partage</h1>   
</header>
<section>
<?php
    while ($torrent = $torrents->fetch()){ ?>

        <article class="cont2torlist">
            <div class="bloc el21torlist">
                <h3><a href="index.php?action=getTorrent&amp;torrent=<?= $torrent['torId'] ?>" title="Cliquez pour télécharger"><?= $torrent['torTitle'] ?></a></h3>

                <div class="cont3torlist">
                    <figure>
                        <?php $texte='[url='.$torrent['torImage'].'][img]'.$torrent['torImage'].'[/img][/url]';
                        include('public/parser.php');
                        echo $texte; ?>
                    </figure>
                    <div class="el31torlist">
                        <?php
                        $torrentHash = $torrent['torHash'];
                        $stats = file_get_contents("http://tk.smilf.cf:6969/stats?mode=tpbs&format=txt");
                        preg_match_all ('#[A-Z0-9]{40}:\d:\d#', $stats, $hash);
                        for($i = 0; $i < count($hash[0]); $i++)
                        {
                            $stat = strtolower($hash[0][$i]);
                            if (preg_match('#'.$torrentHash.'#', $stat)){
                            $stat=preg_replace('#[a-z0-9]{40}:([0-9]+):([0-9]+)#', '$1 seeder / $2 leecher', $stat);
                            echo ('<p>'.$stat.'</p>');
                            }
                        }
                        ?>
                        <p>Catégorie : <strong><?= $torrent['subCat'] ?></strong></p>
                        <p>Uploadé par <a href=""><?= $torrent['memberName'] ?></a></p>
                    </div>
                </div>

                <?php $texte=$torrent['torDescription'];
                include('public/parser.php'); ?>
                <p><?= nl2br($texte) ?></p>
            </div>
            <aside>
                <div class="bloc">
                    <h3><a href="public/torrents/nfo/<?= $torrent['torNfoName'] ?>" title="charger le nfo complet">NFO</a></h3>
                    <ul>
                        <li>Taille du fichier : <strong><?= $torrent['torWeight'] ?> <?= $torrent['torWeightUnit'] ?></strong></li>
                    </ul>
                    <?php
                    if ($torrent['torIdSubCat'] > 0 && $torrent['torIdSubCat'] <= 10){
                        require ('view/frontend/torrents/torrentListView/torrentListVideoView.php');
                    }elseif ($torrent['torIdSubCat'] > 10 && $torrent['torIdSubCat'] <= 15){
                        require ('view/frontend/torrents/torrentListView/torrentListAudioView.php');
                    }
                    ?>
                </div>
            </aside>
        </article>

        <?php
    }
    echo ('</section>');

require ('view/paginView.php');
$content = ob_get_clean();
require ('view/template.php');