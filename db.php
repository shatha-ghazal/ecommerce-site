<?php
// db.php - central DB connection and simple helpers
session_start();

$servername = "localhost";
$database = "ecommerce"; // create with init.sql
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// helpers
function isLoggedIn(){
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
function requireLogin(){
    if(!isLoggedIn()){
        header("Location: login.php");
        exit();
    }
}
?>