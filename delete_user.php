<?php
// Include database connection
include 'db.php';

// Include session handling and admin check
include 'session.php';

// Only allow access if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  // Redirect to login page if unauthorized
  header("Location: login.php");
  exit();
}

// Check if a user ID was provided via GET request (e.g., delete_user.php?id=3)
if (isset($_GET['id'])) {
  // Sanitize the user ID from GET parameter
  $user_id = (int) $_GET['id'];

  // Prevent the admin from deleting their own account
  if ($user_id === $_SESSION['user_id']) {
    echo "You cannot delete your own account.";
    exit;
  }

  // Step 1: Delete user's cart items to prevent foreign key constraint issues
  $stmtCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmtCart->bind_param("i", $user_id);
  $stmtCart->execute();
  $stmtCart->close();

  // Step 2: Delete the user from the users table
  $stmtUser = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmtUser->bind_param("i", $user_id);
  $stmtUser->execute();
  $stmtUser->close();

  // Redirect to user management page after deletion
  header("Location: users.php");
  exit();
} else {
  // Show error if no valid user ID is provided
  echo "Invalid request.";
}
?>
