<?php
session_start();

/* -----------------------------
   RENDER ENV VARIABLES
------------------------------*/
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT') ?: 3306;

/* -----------------------------
   PDO CONNECTION
------------------------------*/
try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
