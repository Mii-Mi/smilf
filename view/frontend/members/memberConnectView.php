<?php
$title = 'Connexion - Smilf.cf';
ob_start()
?>
<header>
    <p class="logo"><a href="index.php"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à l'accueil"/></a></p>
</header>
<section>
<h1>Connexion</h1>

<form class="formbloc" method="POST" action="index.php?action=memberConnect&amp;view=welcome">
    <p><label for="pseudo">Votre pseudo : </label><input type="text" id="pseudo" name="pseudo" /></p>
    <p><label for="pass">Votre mot de passe : </label><input type="password" name="pass" id="pass" /></p>
    <p><label for="remember">Se souvenir de moi</label><input type="checkbox" name="remember" id="remember" /></p>
    <p><input class="submit" type="submit" value="Se connecter" /></p>
</form>
</section>

<?php
$content = ob_get_clean();
require ('view/template.php');