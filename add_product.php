<?php
include 'db.php';
include 'session.php';

// Only admins allowed
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $desc = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $img = trim($_POST['image']);

  // Basic validation
  if ($name === '' || $desc === '' || $price <= 0 || $img === '') {
    $message = 'Please fill in all fields correctly.';
  } else {
    // Prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $desc = mysqli_real_escape_string($conn, $desc);
    $img = mysqli_real_escape_string($conn, $img);

    $sql = "INSERT INTO products (name, description, price, image)
            VALUES ('$name', '$desc', $price, '$img')";
    
    if (mysqli_query($conn, $sql)) {
      header("Location: admin.php");  // Redirect after successful insert
      exit();
    } else {
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
  <link rel="stylesheet" href="add_product.css">
</head>
<body>
  <div class="add-product-container">
    <h1>Add Product</h1>

    <?php if ($message): ?>
      <div class="error-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required />

      <label for="description">Description</label>
      <textarea id="description" name="description" required></textarea>

      <label for="price">Price</label>
      <input type="number" id="price" name="price" step="0.01" min="0" required />

      <label for="image">Image URL</label>
      <input type="text" id="image" name="image" required />

      <button type="submit">Add Product</button>
    </form>
  </div>
</body>
</html>
