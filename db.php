<?php
session_start();

/* -----------------------------
   ENV VARIABLES (Railway)
------------------------------*/
$host = getenv('MYSQLHOST');
$db   = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$port = getenv('MYSQLPORT');

/* -----------------------------
   OPTIONAL APP PORT (Railway web server)
------------------------------*/
$appPort = getenv('PORT') ?: 8000;

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
?>
