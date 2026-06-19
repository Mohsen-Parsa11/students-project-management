<?php
session_start();
require_once __DIR__ . "/../config/functions.php";
// Clear all session data and destroy the session
$_SESSION = [];
session_destroy();
header("Location: " . base_path() . "/auth/login.php");
exit;
?>
