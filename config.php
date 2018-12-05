<?php

ob_start();

$serverName = 'localhost';
$database = 'voogle';
$uid = 'virtus';
$pwd = 'password';
try {
    $conn = new PDO(
        "sqlsrv:server=$serverName;Database=$database",
        $uid,
        $pwd,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
}
catch(PDOException $e) {
    die('Error connecting to SQL Server: ' . $e->getMessage());
}