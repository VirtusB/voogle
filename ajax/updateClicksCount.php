<?php
require_once '../config.php';

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

if ($contentType === 'application/json') {
    $content = trim(file_get_contents('php://input'));

    $decoded = json_decode($content, true);

    $idToUpdate = intval($decoded['payload']['idToUpdate']);
    $type = $decoded['payload']['typeToUpdate'];

    if (isset($idToUpdate, $type)) {
        if ($type === 'site') {
            $query = $conn->prepare("UPDATE sites SET clicks = coalesce(clicks + 1, clicks, 1) WHERE id = :id");
        } else if ($type === 'image') {
            $query = $conn->prepare("UPDATE images SET clicks = coalesce(clicks + 1, clicks, 1) WHERE id = :id");
        }
        $query->bindParam(':id',$idToUpdate, PDO::PARAM_INT);
        $query->execute();
    }
}