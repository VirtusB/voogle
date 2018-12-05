<?php

$text = file_get_contents('sites.txt');

$lines = explode(PHP_EOL, $text);

$sites = [];
$sites[] = 'https://moz.com/top500';
$sites[] = 'https://gtmetrix.com/top1000.html';

foreach($lines as $line){
    $array = explode(" ", $line);
    if ($array[0] && null !== $array[0] && $array[0] !== '') {
        $sites[] = $array[0];
    }
}

$json = json_encode($sites, JSON_UNESCAPED_SLASHES);
file_put_contents('sites.json', $json);