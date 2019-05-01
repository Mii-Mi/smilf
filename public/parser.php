<?php
$texte=preg_replace('#\[b\](.*)\[/b\]#isU', '<strong>$1</strong>', $texte);
$texte=preg_replace('#\[i\](.*)\[/i]#isU', '<em>$1</em>', $texte);
$texte=preg_replace('#\[color=(red|blue|yellow|green|white|black|purple|\#[A-Za-z0-9]{6})\](.*)\[/color\]#isU','<span style="color:$1">$2</span>', $texte);
$texte=preg_replace('#\[url\]((https?://)?(((w{3}|w{2})\.)?[a-z_.\d-]+\.[a-z]{2,4}.*))\[/url\]#i','<a href="$1">$3</a>', $texte);
$texte=preg_replace('#\[url=((https?://)?(((w{3}|w{2})\.)?[a-z_.\d-]+\.[a-z]{2,4}.*))\](.*)\[/url\]#isU','<a href="$1">$6</a>', $texte);
$texte=preg_replace('#\[u\](.*)\[/u]#isU', '<span style="text-decoration:underline">$1</span>', $texte);
$texte=preg_replace('#\[s\](.*)\[/s\]#isU', '<span style="text-decoration:line-through">$1</span>', $texte);
$texte=preg_replace('#\[img\]((https?://)?(((w{3}|w{2})\.)?[a-z_.\d-]+\.([a-z]{2,4}).*))\[/img\]#iU', '<img src="$1" alt="image user" />', $texte);
$texte=preg_replace('#\[center\](.*)\[/center\]#siU', '<div style="text-align:center">$1</div>', $texte);