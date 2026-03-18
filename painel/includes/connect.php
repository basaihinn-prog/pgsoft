<?php
$host = "localhost";
$port = "5432";
$dbname = "joaov";
$user = "postgres";
$password = "root123";

try {
    $conn = new PDO("pgsql:host={$host};port={$port};dbname={$dbname}", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET CLIENT_ENCODING TO 'UTF8'");
} catch (PDOException $e) {
    error_log('Connection failed: ' . $e->getMessage());
    $conn = false;
}
?>

