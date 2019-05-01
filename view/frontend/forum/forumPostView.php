<?php
$title='commentaires';
ob_start();
?>
    <header>
        <p class="logo"><a href="index.php?action=forumList"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à la liste"/></a></p>
    </header>
    <section>
        <div class="bloc">
            <h2><?= $post['title'] ?></h2>
            <p class="bloc"><strong><?= $post['pseudo'] ?></strong> <em>a dit</em> :</p>
            <p class="datePub"><em>Le <?= $post['dateMsg'] ?></em></p>
            <p><?= nl2br($texte) ?></p>
            <?php $_SESSION['read'.$post['ID']] = time(); ?>           
        </div>
        <p></p>

        <div class="cont1com">
            <div class="el1com">
                <?php while ($com=$coms->fetch()){
                    if (isset($_SESSION['pseudo']) && $numMsg == $nbComs && $com['author'] == $_SESSION['pseudo']){
                        $editButton = '<a href="index.php?action=editForm&amp;post='.$post['ID'].'&amp;com='.$com['ID'].'">Modifier</a>';
                        $_SESSION[$com['ID'].'edit'] = true;
                    }elseif (isset($_SESSION['group']) && $_SESSION['group'] <= 2){
                        $editButton = '<a href="index.php?action=editForm&amp;post='.$post['ID'].'&amp;com='.$com['ID'].'">Modifier</a>';
                        $_SESSION[$com['ID'].'edit'] = true;
                    }else{
                        $editButton = '';
                    }
                    $texte=$com['comment'];
                    include('public/parser.php');
                    echo ('
                    <p><div class="bloc">
                    <p class="bloc">#'.$numMsg.' <strong>'.$com['author'].'</strong> <em>a dit</em> :</p>
                    <p class="datePub"><em>Le '.$com['dateCom'].'</em></p>
                    <p id="'.$com['ID'].'">'.nl2br($texte).'</p>
                    <p><a href="index.php">Accueil</a>  <a href="index.php?action=forumList">Billets</a> '.$editButton.'</p>
                    </div>
                    ');
                    $numMsg ++;
                }

                require ('view/paginView.php'); 

                if (isset($_SESSION['pseudo'])){
                    if ($_SESSION['group'] <= 2 || !isset($_SESSION[$com['ID'].'edit'])){
                    ?>
                        <p><form class="formbloc" id="form" method="POST" action="index.php?action=addCom">
                            <input type="hidden" name="post" value=<?= $post['ID']?>/>
                            <p><label for="comment">Votre réponse : </label><textarea name="comment" id="comment" rows="6" cols="200"></textarea></p>
                            <input type="hidden" name="pseudo" id="pseudo" value="<?= $_SESSION['pseudo'] ?>"/>
                            <p><input class="submit" type="submit" value="envoyer" /></p>
                        </form>
                    <?php 
                    }else{
                        echo('<p class="bloc">Vous ne pouvez pas écrire deux messages de suite !<br />Utilisez plutôt la fonction "modifier" pour éditer votre message.</p>');
                    }
                }else{
                    echo('<p class="bloc"><a href="index.php"><strong>Connectez-vous pour écrire un commentaire</strong></a></p>');
                }
                ?>
            </div>

            <div id="lisere">
                <p> </p>
            </div>

        </div>
    </section>
<?php
$content=ob_get_clean();
require ('view/template.php');