<?php
// Include the database connection file
include 'db.php';

// Include session and access control functions
include 'session.php';

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  // Redirect to login page if not authorized
  header("Location: login.php");
  exit();
}

// Get the product ID from the URL query string (e.g., delete_product.php?id=5)
// If not provided, default to 0
$id = $_GET['id'] ?? 0;

// Step 1: Optionally delete the image file associated with the product
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM products WHERE id = $id"));
if ($product && file_exists("uploads/" . $product['image'])) {
    // Remove image file from the server
    unlink("uploads/" . $product['image']);
}

// Step 2: Delete the product from the database
mysqli_query($conn, "DELETE FROM products WHERE id = $id");

// Redirect back to the product management page
header("Location: manage_products.php");
exit();
?>
