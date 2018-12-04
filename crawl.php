<?php
require_once 'classes/DomDocumentParser.php';

function createLink($src, $url) {
    $scheme = parse_url($url)['scheme'];
    $host = parse_url($url)['host'];
    $path = @parse_url($url)['path'];

    if (substr($src, 0, 2) === '//') {
        $src = $scheme . ':' . $src;
    } else if (substr($src, 0, 1) === '/') {
        $src = $scheme . '://' . $host . $src;
    } else if (substr($src, 0, 2) === './') {
        $src = $scheme . '://' . $host . dirname($path) . substr($src, 1);
    } else if (substr($src, 0, 3) === '../') {
        $src = $scheme . '://' . $host . '/' . $src;
    } else if (substr($src, 0, 4) !== 'http' && substr($src, 0, 5) !== 'https') {
        $src = $scheme . '://' . $host . '/' . $src;
    }

    return $src;
}

function followLinks(string $url) {
    $parser = new DomDocumentParser($url);

    $linkList = $parser->getLinks();

    foreach ($linkList as $link) {
        $href = $link->getAttribute('href');

        if (strpos($href, '#') !== false) {
            continue;
        }

        if (0 === strpos($href, 'javascript')) {
            continue;
        }

        $href = createLink($href, $url);
        echo $href, '<br>';
    }
}

$startUrl = 'https://www.bbc.com';
followLinks($startUrl);