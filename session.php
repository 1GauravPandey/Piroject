<?php
// Start the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define session timeout period (15 minutes = 900 seconds)
const SESSION_TIMEOUT = 900;

// Check if last activity timestamp exists and if session has timed out
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
    // If timeout exceeded, clear session data and destroy session
    session_unset();
    session_destroy();
    // Redirect user to login page with timeout flag
    header("Location: login.php?timeout=1");
    exit;
}

// Update last activity timestamp to current time
$_SESSION['LAST_ACTIVITY'] = time();

/**
 * Require a logged-in customer (non-admin) for access control.
 * Redirects to login page if user is not logged in as customer.
 * Stores the requested page in session to redirect back after login.
 */
function require_customer() {
    // Check if user_id is empty OR
    // is_admin is not set OR
    // user is admin (we want only non-admin customers here)
    if (empty($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin']) {
        // Save the originally requested page only for GET requests (avoid POST forms)
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        } else {
            // For POST or other requests, redirect to homepage after login by default
            $_SESSION['redirect_after_login'] = 'index.php';
        }
        // URL-encode message to show on login page
        $msg = urlencode("Please Login to Continue your Order");
        // Redirect to login page with message and require_login flag
        header("Location: login.php?require_login=1&msg=$msg");
        exit;
    }
}

/**
 * Returns true if user is logged in (user_id is set)
 */
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

/**
 * Returns true if user is an admin (is_admin flag set)
 */
function is_admin() {
    return !empty($_SESSION['is_admin']);
}
