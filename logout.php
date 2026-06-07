<?php
require_once 'dbe.php';
session_unset();
session_destroy();
header("Location: login.php");
exit;