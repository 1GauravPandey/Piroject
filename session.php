<?php
// session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 15 minutes
const SESSION_TIMEOUT = 900;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

/**
 * Require a logged-in *customer*.
 * (Use a separate guard for admins if you want.)
 */
function require_customer() {
    if (empty($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin']) {
        // Only remember if it's a GET request (avoid POST destinations like add_to_cart.php)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
} else {
    $_SESSION['redirect_after_login'] = 'index.php';
}
        $msg = urlencode("Please Login to Continue your Order");
        header("Location: login.php?require_login=1&msg=$msg");
        exit;
    }
}

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function is_admin() {
    return !empty($_SESSION['is_admin']);
}


