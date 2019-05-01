<?php 
$title = 'Posts';
ob_start();
?>

<header>
    <p class="logo"><a href="index.php"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à l'accueil"/></a></p>
    <h1>Les derniers billets actifs !</h1>
</header>
<article>
    <p class="formbloc"><a href="index.php?action=markRead">Marquer tous les billets comme lus</a></p>
    <?php
    while ($post= $posts->fetch()){
        if( $post['tStamp'] >= $_SESSION['lastVisit'] ){
            if(isset($_SESSION['read'.$post['ID']]) && $_SESSION['read'.$post['ID']] >= $post['tStamp']){
                $status = '<img src="'.$read.'" alt="message lu" title="message lu" />';
            }else{
                $status = '<img src="'.$unread.'" alt="message non-lu" title="message non-lu" />';
            }
        }else{
            $status = '<img src="'.$read.'" alt="message lu" title="message lu" />';
        }
        $texte=$post['message'];
        include('public/parser.php');
        echo '<p><div class=bloc>
        <div class="contStatusTitle">
            <p>' . $status . '</p><h3>' . $post['title'] . '</h3>
        </div>
        <p class="postsListContent"><em>Par</em> <strong>' . $post['pseudo'] . '</strong>, <em>' . $post['dateMsg'] .'</em><br />
        <a href="index.php?action=getPost&amp;post='.$post['ID'].'">Voir le billet</a>  <a href="index.php">Retour à l\'accueil</a>  <a href="#form">Nouveau</a></p>
        </div>';
    }
    require ('view/paginView.php');

    if (isset($_SESSION['pseudo'])){
    ?>
        <p><form class="formbloc" id="form" method="post" action="index.php?action=addPost">
            <p><label for="title">Titre : </label><input type="text" name="title" id="title" size="210" maxlength="80" required /></p>
            <p><label for="message">Message : </label><textarea name="message" id="message" rows="6" cols="240"></textarea></p>
            <input type="hidden" name="pseudo" id="pseudo" value="<?= $_SESSION['pseudo'] ?>"/>
            <p><input class="submit" type="submit" value="Envoyer"/></p>
        </form>
    <?php }else{
        echo ('<p class="formbloc"><a href="index.php"><strong>Connectez-vous pour écrire un nouveau billet</stong></a></p>');
    } ?>
</article>
<?php
$content = ob_get_clean();
require ('view/template.php');