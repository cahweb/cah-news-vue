<?php
$json = json_decode(file_get_contents('news-posts.json'));
$fh = fopen('news-posts.json', 'w');
fwrite($fh, json_encode($json, JSON_PRETTY_PRINT));
fclose($fh);
