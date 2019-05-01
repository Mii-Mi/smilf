<p><label for="format">Format : </label><input type="text" name="format" id="format" value="<?= $_SESSION['form']['format'] ?>" required /></p>
<p><label for="quality">Qualité : </label><input type="text" name="quality" id="quality" value="<?= $_SESSION['form']['quality'] ?>" required /></p>
<h3>Video</h3>
<p><label for="vCodec">Codec video : </label><input type="text" name="vCodec" id="vCodec" value="<?= $_SESSION['form']['vCodec'] ?>" required /></p>
<p><label for="vBitrate">Bitrate video : </label><input type="number" name="vBitrate" id="vBitrate" value="<?= $_SESSION['form']['vBitrate'] ?>" min="0" required /> kbps</p>
<h3>Audio</h3>
<p><label for="language">Langue : </label><input type="text" name="language" id="language" value="<?= $_SESSION['form']['language'] ?>" required /></p>
<p><label for="aCodec">Codec audio : </label><input type="text" name="aCodec" id="aCodec" value="<?= $_SESSION['form']['aCodec'] ?>" required /></p>
<p><label for="aBitrate">Bitrate audio : </label><input type="number" name="aBitrate" id="aBitrate" value="<?= $_SESSION['form']['aBitrate'] ?>" min="0" required /> kbps</p>