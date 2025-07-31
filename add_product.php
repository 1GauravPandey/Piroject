<?php
// Include database connection and session check
include 'db.php';
include 'session.php';

// Allow only admin users to access this page
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php"); // Redirect to login if not admin
  exit();
}

$message = ''; // Initialize empty message for error/success feedback

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get and sanitize form inputs
  $name = trim($_POST['name']);
  $desc = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $img = trim($_POST['image']);
  $category = trim($_POST['category']);

  // Basic validation to check all fields are filled
  if ($name === '' || $desc === '' || $price <= 0 || $img === '' || $category === '') {
    $message = 'Please fill in all fields correctly.';
  } else {
    // Escape input to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $desc = mysqli_real_escape_string($conn, $desc);
    $img = mysqli_real_escape_string($conn, $img);
    $category = mysqli_real_escape_string($conn, $category);

    // SQL query to insert the product into the database
    $sql = "INSERT INTO products (name, description, price, image, category)
            VALUES ('$name', '$desc', $price, '$img', '$category')";

    // Run the query
    if (mysqli_query($conn, $sql)) {
      header("Location: admin.php"); // Redirect to admin panel on success
      exit();
    } else {
      // Show error message on failure
      $message = 'Database error: ' . mysqli_error($conn);
    }
  }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Product - Admin | Jutta Sansaar</title>

  <!-- Link to external CSS file -->
  <link rel="stylesheet" href="add_product.css">
</head>
<body>

  <!-- Container for the Add Product form -->
  <div class="add-product-container">
    <h1>Add Product</h1>

    <!-- Display error/success message if exists -->
    <?php if ($message): ?>
      <div class="error-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Product input form -->
    <form method="POST" action="">
      <!-- Product name input -->
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required />

      <!-- Product description input -->
      <label for="description">Description</label>
      <textarea id="description" name="description" required></textarea>

      <!-- Product price input -->
      <label for="price">Price</label>
      <input type="number" id="price" name="price" step="0.01" min="0" required />

      <!-- Image URL input -->
      <label for="image">Image URL</label>
      <input type="text" id="image" name="image" required />

      <!-- Product category selection -->
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="" disabled selected>Select category</option>
        <option value="Sports">Sports</option>
        <option value="Casual">Casual</option>
        <option value="Formal">Formal</option>
        <option value="Kids">Kids</option>
        <option value="Boots">Boots</option>
        <option value="Sandals">Sandals</option>
      </select>

      <!-- Submit button -->
      <button type="submit">Add Product</button>
    </form>

  </div>
</body>
</html>
