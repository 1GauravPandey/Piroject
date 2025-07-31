<?php
// Include database connection and session management
include 'db.php';
include 'session.php';

// Restrict access to logged-in admins only
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  // Redirect unauthorized users to login page
  header("Location: login.php");
  exit();
}

// Process form submission via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get order ID and action from POST data
  $order_id = $_POST['order_id'];
  $action = $_POST['action'];

  // Determine status based on action value
  if ($action === 'accept') {
    $status = 'Accepted';
  } elseif ($action === 'reject') {
    $status = 'Rejected';
  } else {
    $status = 'Pending'; // Default/fallback status
  }

  // Update the order's status in the database
  $query = "UPDATE orders SET status='$status' WHERE id=$order_id";
  mysqli_query($conn, $query);
}

// Redirect back to the admin dashboard after processing
header("Location: admin.php");
exit();
