<?php
include 'db.php';

include 'session.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
  $id = intval($_GET['id']);
  $status = $_GET['status'];

  if (in_array($status, ['Accepted', 'Rejected'])) {
    $query = "UPDATE orders SET status='$status' WHERE id=$id";
    mysqli_query($conn, $query);
  }
}

header("Location: admin.php");
exit();
?>
