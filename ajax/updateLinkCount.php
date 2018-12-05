<?php
require_once '../config.php';

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

if ($contentType === 'application/json') {
    $content = trim(file_get_contents('php://input'));

    $decoded = json_decode($content, true);

    $linkId = intval($decoded['payload']['linkToUpdate']);

    if (isset($linkId)) {
        $query = $conn->prepare("UPDATE sites SET clicks = coalesce(clicks + 1, clicks, 1) WHERE id = :id");
        $query->bindParam(':id',$linkId, PDO::PARAM_INT);
        $query->execute();
    }
}