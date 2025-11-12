<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function is_logged_in() {
    return isset($_SESSION['user']);
}

function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function require_admin() {
    if (!is_admin()) {
        header("Location: /public/login.php?e=forbidden");
        exit;
    }
}
?>
