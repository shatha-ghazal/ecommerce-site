<?php
session_start();

$host = "fdb1032.awardspace.net";
$db   = "4766581_ecommerce";
$user = "4766581_ecommerce";
$pass = "abcabc123*";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
