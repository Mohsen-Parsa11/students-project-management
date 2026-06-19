<?php
// ============================================
// Session + Role Helpers
// Include this at the top of any protected page.
// ============================================

if (session_status() === PHP_SESSION_NONE) {
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
        // Logged in but wrong role -> bounce to their own dashboard
        if ($_SESSION['role'] === 'admin') {
            header("Location: " . base_path() . "/admin/dashboard.php");
        } else {
            header("Location: " . base_path() . "/student/dashboard.php");
        }
        exit;
    }
}
?>
