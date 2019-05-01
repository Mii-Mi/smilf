<?php
$title = 'Accueil - smilf.cf';
ob_start() ?>
<div class="logoTitre"><p class="logo"><a href="index.php"><img src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à l'accueil"/></a></p><h1>Bienvenue chez Smilf !</h1></div>
<div class="cont1main">
	<header class="el1main">
		<p class="smilf_big" ><a href=""><img class="rotateSmilf" src="public/images/logo_smilf_big.png" alt="Smilfette" title="Smilfette" /></a></p>
	</header>

	<nav class="bloc el2main">
		<h2><?= $member ?></h2>
		<ul>
			<?= $register ?>
			<li><?= $connect ?></li>
		</ul>
		<h2>Plan du site</h2>
		<ul>
			<li><a href="index.php">Page d'accueil</a></li>
			<?= $shareSpaceLink ?>
			<li><a href="https://wiki.smilf.cf">SmilfWiki</a></li>
			<li><a href="https://chat.smilf.cf/channel/Accueil">SmilfChat</a></li>
		</ul>
	</nav>
	<aside class="bloc el3main">
		<h2>Dernier billet</h2>
		<?php
			$texte=$post['message'];
			include('public/parser.php');
			echo '<p class=bloc><strong>' . $post['pseudo'] . '</strong>' . ' <em>a dit</em> : <br />' . nl2br($texte) . '</p>';				
		?>
		<p><a href="index.php?action=forumList">Voir tous les billets</a><br />
		<a href="index.php?action=getPost&amp;post=<?= $post['ID'] ?>">Voir les réponses</a></p>
	</aside>
</div>

<section>
	<h1>Bienvenue chez Smilf !</h1>

	<div class="bloc">
	<p>Inscrivez-vous pour bénéficier des fonctionnalités de partage !</p>
	</div>
</section>

<footer id="footMainCont">
	<a id="footMainEl1" href="mailto:smilf@free.fr">Contactez-moi</a>
</footer>

<?php
$content = ob_get_clean();
require ('view/template.php');
?>