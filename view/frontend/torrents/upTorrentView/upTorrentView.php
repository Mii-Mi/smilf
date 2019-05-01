<?php
$title = 'upload - smilf.cf';
ob_start()
?>
<header>
    <div class="logo">
        <p><a href="index.php?action=share"><img class="rotateSmilf" src="public/images/logo_smilf_small.png" alt="logo Smilfette" title="Retourner à la liste"/></a></p>
    </div>
    <h1>Uploader un torrent</h1>
    <h4><strong>adresse du tracker</strong> : http://tk.smilf.cf:6969/announce</h4>
</header>
<section>
    <h2><?= $subcat ?></h2>

    <form class="formbloc" action="index.php?action=addTorrent" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idSubCat" value="<?= $catForm ?>" />
        <p>
            <label for="weight">Poids total du post : </label><input type="text" name="weight" id="weight" min="0" value="<?= $_SESSION['form']['weight'] ?>" required />
            <input type="radio" name="unit" value="Ko" id="ko"><label for="ko">Ko</label>
            <input type="radio" name="unit" value="Mo" id="mo" checked><label for="mo">Mo</label>
            <input type="radio" name="unit" value="Go" id="go"><label for="go">Go</label>
        </p>
        <p><label for="torrent">Fichier torrent : </label><input type="file" name="torrent" id="torrent" required /></p>
        <p><label for="nfo">Fichier NFO : </label><input type="file" name="nfo" id="nfo" required /></p>
        <?= $special ?>
        <h3>Général</h3>
        <p><label for="title">Titre de l'upload : </label><input type="text" name="title" id="title" value="<?= $_SESSION['form']['title'] ?>" required /></p>
        <p><label for="image">Lien vers l'image : </label><input type="url" name="image" id="image" value="<?= $_SESSION['form']['image'] ?>" required /></p>
        <p><label for="description">Brève description : </label><textarea name="description" id="description" rows="6" cols="200" maxlength="2000" placeholder="Pas de lien, pas d'image, pas de bannière. 2000 caractères max." required><?= $_SESSION['form']['description'] ?></textarea></p>
        <p><label for="free">Informations complémentaires : </label><textarea name="free" id="free" rows="6" cols="200" placeholder="(Votre prez habituelle) Libre et facultatif. Attention d'éviter le double emploi avec les informations déjà données ! (notamment l'image principale, les bitrates, les codecs, etc...)"><?= $_SESSION['form']['free'] ?></textarea></p>

        <?php require ($upForm) ?>

        <p><input class="submit" type="submit" value="Uploader !" /></p>
    </form>
</section>
<?php
$content = ob_get_clean();
require ('view/template.php');