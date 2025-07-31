<?php
// Include database connection and session management
include 'db.php';
include 'session.php';

// Only allow access if the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
  // Redirect non-admin users to login page
  header("Location: login.php");
  exit();
}

// Check if 'id' and 'status' parameters are provided via GET
if (isset($_GET['id']) && isset($_GET['status'])) {
  // Sanitize the order ID as integer
  $id = intval($_GET['id']);
  // Get the status from the query string
  $status = $_GET['status'];

  // Validate that the status is either 'Accepted' or 'Rejected'
  if (in_array($status, ['Accepted', 'Rejected'])) {
    // Update the order status in the database
    $query = "UPDATE orders SET status='$status' WHERE id=$id";
    mysqli_query($conn, $query);
  }
}

// Redirect back to the admin dashboard after updating
header("Location: admin.php");
exit();
?>
