<?php
include 'db.php';
include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

if (isset($_GET['id'])) {
  $user_id = (int) $_GET['id'];

  if ($user_id === $_SESSION['user_id']) {
    echo "You cannot delete your own account.";
    exit;
  }

  // Delete from cart first to prevent foreign key error
  $stmtCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmtCart->bind_param("i", $user_id);
  $stmtCart->execute();
  $stmtCart->close();

  // Now delete user
  $stmtUser = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmtUser->bind_param("i", $user_id);
  $stmtUser->execute();
  $stmtUser->close();

  header("Location: users.php");
  exit();
} else {
  echo "Invalid request.";
}
?>
