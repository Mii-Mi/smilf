<p></p>
<div class="contPagin bloc">
        <p class="el1Pagin">Page <?= $page ?> sur <?= $nbDePages ?></p>
        <p><a href="index.php?action=<?= $action ?>&amp;page=1" title="Première page">&lt;&lt;</a></p>
    <?php if ($page !== 1){?>
        <p><a href="index.php?action=<?= $action ?>&amp;page=<?= $page -1 ?>" title="Page précédente">&lt;</a></p>
    <?php }
    else{
        echo ('<p>&lt;</p>');
    } ?>
        <form class="formPage" method="post" action="index.php?action=<?= $action ?>"><input class="selection" type="number" name="page" id="page" max="<?= $nbDePages ?>" min="1" placeholder="1"/><input class="submit" type="submit" value="Aller"/> </form>
    <?php if ($page < $nbDePages){?>
        <p><a href="index.php?action=<?= $action ?>&amp;page=<?= $page +1 ?>" title="Page suivante">&gt;</a></p>
    <?php }
    else{
        echo ('<p>&gt;</p>');
    } ?>
        <p><a href="index.php?action=<?= $action ?>&amp;page=<?= $nbDePages ?>" title="Dernière page">&gt;&gt;</a></p>
</div>