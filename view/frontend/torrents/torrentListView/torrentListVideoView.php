<?php
if (!empty($torrent['torSeason']) && !empty($torrent['torEp']) && !empty($torrent['torStatus'])){ ?>
    <h5>La série</h5>
    <ul>
        <li>Nb de saisons : <?= $torrent['torSeason'] ?></li>
        <li>Nb d'épisodes / saison : <?= $torrent['torEp'] ?></li>
        <li>Statut série : <?= $torrent['torStatus'] ?></li>
    </ul>
<?php
}
?>
<h5>Vidéo</h5>
<ul>
    <li>Format : <?= $torrent['torFormat'] ?></li>
    <li>Qualité : <?= $torrent['torQuality'] ?></li>
    <li>codec : <?= $torrent['torVcodec'] ?></li>
    <li>bitrate vidéo : <?= $torrent['torVbitrate'] ?> kbps</li>
</ul>
<h5>Audio</h5>
<ul>
    <li>Langue : <?= $torrent['torLanguage'] ?></li>
    <li>Codec : <?= $torrent['torAcodec'] ?></li>
    <li>bitrate : <?= $torrent['torAbitrate'] ?> kbps</li>
</ul>