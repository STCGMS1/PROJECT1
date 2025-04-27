<?php
$host = "localhost";
$port = "5432";
$dbname = "stc_graveyards";
$user = "postgres";
$password = "1234";

try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>