<?php
$title = 'Inscription';
ob_start()
?>
<header>
    <p class="logo"><a href="index.php"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à l'accueil"/></a></p>
</header>
<section>
    <h1>Je deviens membre !</h1>

    <p><form class ="formbloc" action="index.php?action=addMember" method = "post">
        <p><label for="pseudo">pseudo : </label><input type="text" name="pseudo" id="pseudo" required /></p>
        <p><label for="email">E-mail : </label><input type="email" name="email" id="email" required /></p>
        <p><label for="pass">Saisissez un mot de passe : </label><input type="password" name="pass" id="pass" required /></p>
        <p><label for="pass2">Répétez le mot de passe : </label><input type="password" name="pass2" id="pass2" required /></p>
        <p><input class="submit" type="submit" value="S'inscrire" /></p>
    </form>
</section>

<?php
$content = ob_get_clean();
require ('view/template.php');
?>