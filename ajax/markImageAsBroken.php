<?php
require_once '../config.php';

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

if ($contentType === 'application/json') {
    $content = trim(file_get_contents('php://input'));

    $decoded = json_decode($content, true);

    $imageId = intval($decoded['payload']['brokenImageId']);

    if (isset($imageId)) {
        $query = $conn->prepare("UPDATE images SET broken = 1 WHERE id = :id");
        $query->bindParam(':id',$imageId, PDO::PARAM_INT);
        $query->execute();
    }
}