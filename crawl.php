<?php
require_once 'config.php';
require_once 'classes/DomDocumentParser.php';

$alreadyCrawled = [];
$crawling = [];
$alreadyFoundImages = [];

function insertLink($url, $title, $description, $keywords) {
    global $conn;

    $query = $conn->prepare("INSERT INTO sites(url, title, description, keywords) VALUES(:url, :title, :description, :keywords)");
    $query->bindParam(':url', $url);
    $query->bindParam(':title', $title);
    $description = utf8_encode($description);
    $query->bindParam(':description', $description);
    $query->bindParam(':keywords', $keywords);

    return $query->execute();
}

function insertImage($url, $src, $alt, $title) {
    global $conn;

    $query = $conn->prepare("INSERT INTO images(site_url, image_url, alt, title) VALUES(:site_url, :image_url, :alt, :title)");
    $query->bindParam(':site_url', $url);
    $query->bindParam(':image_url', $src);
    $query->bindParam(':alt', $alt);
    $query->bindParam(':title', $title);

    return $query->execute();
}

function linkExists($url) {
    global $conn;

    $query = $conn->prepare("SELECT * FROM sites WHERE url = :url");
    $query->bindParam(':url', $url);
    $query->execute();

    return $query->rowCount() < 0;
}

function imageExists($src) {
    global $conn;

    $query = $conn->prepare("SELECT * FROM images WHERE image_url = :image_url");
    $query->bindParam(':image_url', $src);
    $query->execute();

    return $query->rowCount() < 0;
}

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

function getDetailsForPage(string $url, DomDocumentParser $parser) {
    global $alreadyFoundImages;

    $titleArray = $parser->getTitleTags();
    if (!is_object($titleArray) || count($titleArray) === 0) {
        return;
    }

    $title = $titleArray->item(0)->nodeValue;
    $title = str_replace("\n", '', $title);

    if ($title === '') {
        return;
    }

    $description = '';
    $keywords = '';
    $metasArray = $parser->getMetaTags();
    foreach ($metasArray as $meta) {
        if ($meta->getAttribute('name') === 'description') {
            $description = str_replace("\n", '', $meta->getAttribute('content'));
        }

        if ($meta->getAttribute('name') === 'keywords') {
            $keywords = str_replace("\n", '', $meta->getAttribute('content'));
        }
    }

    if (!linkExists($url)) {
        insertLink($url, $title, $description, $keywords);
    }

    $imageArray = $parser->getImages();
    foreach ($imageArray as $image) {
        $src = $image->getAttribute('src');
        $alt = $image->getAttribute('alt');
        $title = $image->getAttribute('title');

        if (!$title && !$alt) {
            return;
        }

        $src = createLink($src, $url);
        $hash = array_flip($alreadyFoundImages);
        if (!isset($hash[$src])) {
            $alreadyFoundImages[] = $src;
            if (!imageExists($src)) {
                insertImage($url, $src, $alt, $title);
            }
        }
    }
}

function followLinks(string $url) {
    global $alreadyCrawled;
    global $crawling;

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

        $hash = array_flip($alreadyCrawled);
        if (!isset($hash[$href])) {
            $alreadyCrawled[] = $href;
            $crawling[] = $href;

            getDetailsForPage($href, $parser);
        }
    }

    array_shift($crawling);

    foreach ($crawling as $site) {
        followLinks($site);
    }
}

$startUrls = json_decode(file_get_contents('sites.json'));

//for ($i = 0; $i < 5; $i++) {
//    followLinks($startUrls[$i]);
//}
$current = 0;
foreach ($startUrls as $url) {
    $current++;
    followLinks($url);
    echo "Crawler $current<br>";
}




