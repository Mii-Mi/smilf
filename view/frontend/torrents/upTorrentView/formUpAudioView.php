<input type="hidden" name="format" id="format" value="" /></p>
<input type="hidden" name="quality" id="quality" value="" /></p>
<input type="hidden" name="vCodec" id="vCodec" value="" /></p>
<input type="hidden" name="vBitrate" id="vBitrate" value="" />
<h3>Audio</h3>
<input type="hidden" name="language" id="language" value="" /></p>
<p><label for="aCodec">Codec audio : </label><input type="text" name="aCodec" id="aCodec" value="<?= $_SESSION['form']['aCodec'] ?>" required /></p>
<p><label for="aBitrate">Bitrate audio : </label><input type="number" name="aBitrate" id="aBitrate" value="<?= $_SESSION['form']['aBitrate'] ?>" min="0" required /> kbps</p>