<?php
$title='commentaires';
ob_start();
?>
    <header>
        <p class="logo"><a href="index.php?action=share"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à la liste"/></a></p>
    </header>
    <section class="cont2tor">
        <article class="bloc el21tor">
            <h3><a href="public/torrents/<?= $torrent['torName'] ?>" title="Cliquez pour télécharger"><?= $torrent['torTitle'] ?></a></h3>

            <div class="cont3tor">
                <?php $texte='[url='.$torrent['torImage'].'][img]'.$torrent['torImage'].'[/img][/url]';
                include('public/parser.php'); 
                echo $texte; ?>
            </div>

            <?php $texte=$torrent['torDescription'];
            include('public/parser.php'); ?>
            <p class="torSynopsis"><?= nl2br($texte) ?></p>
            <?php $texte=$torrent['torFree'];
            include('public/parser.php'); ?>
            <p class="torFree"><?= nl2br($texte) ?></p>  
        </article>
        <aside class="bloc el22tor">
            <h3><a href="public/torrents/nfo/<?= $torrent['torNfoName'] ?>" title="charger le nfo complet">NFO</a></h3>
            <p><?php
                $torrentHash = $torrent['torHash'];
                $stats = file_get_contents("http://tk.smilf.cf:6969/stats?mode=tpbs&format=txt");
                preg_match_all ('#[A-Z0-9]{40}:\d:\d#', $stats, $hash);
                for($i = 0; $i < count($hash[0]); $i++)
                {
                    $stat = strtolower($hash[0][$i]);
                    if (preg_match('#'.$torrentHash.'#', $stat)){
                    $stat=preg_replace('#[a-z0-9]{40}:([0-9]+):([0-9]+)#', '$1 seeder / $2 leecher', $stat);
                    echo $stat;
                    }
                }
            ?></p>
            <ul>
                <li>Taille du fichier : <strong><?= $torrent['torWeight'] ?> <?= $torrent['torWeightUnit'] ?></strong></li>
                <li>Catégorie : <strong><?= $torrent['subCat'] ?></strong></li>
                <li>Uploadé par <a href=""><?= $torrent['memberName'] ?></a></li>
            </ul>
            <?php
            require ('view/frontend/torrents/torrentView/'.$catView);
            ?>
        </aside>
    </section>
    <section>
        <p></p>

        <div class="cont1com">
            <div class="el1com">
                <?php while ($com=$coms->fetch()){
                    $texte=$com['comment'];
                    include('public/parser.php');
                    echo ('
                    <p><div class="bloc">
                    <p class="bloc"><strong>'.$com['author'].'</strong> <em>a dit</em> :</p>
                    <p class="datePub"><em>Le '.$com['dateCom'].'</em></p>
                    <p id="'.$com['id'].'">'.nl2br($texte).'</p>
                    <p><a href="index.php">Accueil</a>  <a href="index.php?action=share">Torrents</a></p>
                    </div>
                    ');
                }
                ?>

                <p><form class="formbloc" id="form" method="POST" action="index.php?action=addTorCom">
                    <input type="hidden" name="torrent" value=<?= $torrent['torId']?>/>
                    <p><label for="comment">Votre commentaire : </label><textarea name="comment" id="comment" rows="6" cols="200"></textarea></p>
                    <input type="hidden" name="pseudo" value="<?= $_SESSION['pseudo'] ?>"/>
                    <p><input class="submit" type="submit" value="envoyer" /></p>
                </form>
            </div>

            <div id="lisere">
                <p> </p>
            </div>

        </div>
    </section>
<?php
$content=ob_get_clean();
require ('view/template.php');