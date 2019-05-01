<?php
$title = 'Modifier message';
ob_start();
?>
<h1>Modifier le message</h1>
<h2><?= $post['title'] ?></h2>
<?php 
$texte=$oldComment['comment'];
include('public/parser.php');
echo ('
<p><div class="bloc">
<p class="bloc"><strong>'.$oldComment['author'].'</strong> <em>a dit</em> :</p>
<p class="datePub"><em>Le '.$oldComment['dateCom'].'</em></p>
<p id="'.$oldComment['ID'].'">'.nl2br($texte).'</p>
<p><a href="index.php">Accueil</a>  <a href="index.php?action=forumList">Billets</a></p>
</div>
');
?>
<p><form class="formbloc"  method="POST" action="index.php?action=editCom&amp;post=<?= $post['ID'] ?>&amp;com=<?= $oldComment['ID'] ?>">
    <p><label for="comment">Votre commentaire : </label><textarea name="comment" id="comment" rows="6" cols="200"><?= $oldComment['comment'] ?></textarea></p>
    <p><input class="submit" type="submit" value="Modifier" /></p>
</form>
<?php
$content = ob_get_clean();
require ('view/template.php');