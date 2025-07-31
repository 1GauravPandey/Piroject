<?php
// Include database connection and session handling
include 'db.php';
include 'session.php';

// Only allow access if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    // Redirect unauthorized users to login page
    header("Location: login.php");
    exit();
}

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and cast incoming data
    $user_id = (int) $_POST['user_id'];
    $is_admin = (int) $_POST['is_admin'];

    // Prevent admin from changing their own admin status to avoid accidental lockout
    if ($user_id === $_SESSION['user_id']) {
        // Redirect back to users page with error message
        header("Location: users.php?error=selfchange");
        exit();
    }

    // Prepare SQL statement to update is_admin flag for the specified user
    $stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_admin, $user_id);
    $stmt->execute();

    // After updating, redirect back to the users management page
    header("Location: users.php");
    exit();
}
?>
