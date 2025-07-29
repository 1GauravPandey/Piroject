<?php
include 'db.php';

include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'] ?? 0;

// Optionally delete image file from uploads
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM products WHERE id = $id"));
if ($product && file_exists("uploads/" . $product['image'])) {
    unlink("uploads/" . $product['image']);
}

// Delete product from database
mysqli_query($conn, "DELETE FROM products WHERE id = $id");

header("Location: manage_products.php");
exit();
