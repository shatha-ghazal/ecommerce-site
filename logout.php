<?php
session_start();

// clear session data
$_SESSION = [];

// destroy session
session_destroy();

// redirect to login
header("Location: login.php");
exit;
