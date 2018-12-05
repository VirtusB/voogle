<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '14G');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
header('Content-Type: text/html; charset=UTF-8');
ini_set('mssql.charset', 'UTF-8');

define('PAGE_SIZE', 20);

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

//    $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
}
catch(PDOException $e) {
    die('Error connecting to SQL Server: ' . $e->getMessage());
}