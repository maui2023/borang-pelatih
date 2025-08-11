<?php
// Database Connection File

// Load environment variables
$env = parse_ini_file(__DIR__ . '/../.env');

$db_host = $env['DB_HOST'];
$db_name = $env['DB_NAME'];
$db_user = $env['DB_USER'];
$db_pass = $env['DB_PASS'];

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db_name :" . $e->getMessage());
}