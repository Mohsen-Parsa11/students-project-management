<?php

if (session_status() === PHP_SESSION_NONE) {
    session_name('SPMS_STUDENT');
    session_start();
}

require_once __DIR__ . "/functions.php";

// Is a user logged in?
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Require login, otherwise send to login page
function require_login() {
    if (!is_logged_in()) {
        header("Location: " . base_path() . "/auth/login.php");
        exit;
    }
}

// Require a specific role (e.g. 'admin' or 'student')
function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        $_SESSION = [];
        session_destroy();
        header("Location: " . base_path() . "/auth/login.php");
        exit;
    }
}
?>
