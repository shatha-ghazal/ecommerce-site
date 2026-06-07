<?php
// db.php - central DB connection and simple helpers
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// Dynamically use Render variables if they exist, otherwise use your local defaults
$servername = getenv('DB_HOST') ?: "dpg-d8irvat8nd3s73dtrecg-a";
$database = getenv('DB_NAME') ?: "ecommerce";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";

// Establish the MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// Change charset to prevent broken text issues
$conn->set_charset("utf8mb4");

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
