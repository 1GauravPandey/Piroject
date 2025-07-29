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
  $img = trim($_POST['image_url']);

  // Basic validation
  if ($name === '' || $desc === '' || $price <= 0 || $img === '') {
    $message = 'Please fill in all fields correctly.';
  } else {
    // Prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $desc = mysqli_real_escape_string($conn, $desc);
    $img = mysqli_real_escape_string($conn, $img);

    $sql = "INSERT INTO products (name, description, price, image_url)
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
  <style>
    /* Container for the form */
    .add-product-container {
      max-width: 480px;
      margin: 3rem auto;
      background: #fff;
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
    }

    /* Form title */
    .add-product-container h1 {
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: 700;
      font-size: 1.8rem;
      color: #ff7e5f;
    }

    /* Form groups */
    .add-product-container label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #555;
    }

    .add-product-container input[type="text"],
    .add-product-container input[type="number"],
    .add-product-container textarea {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1.8px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
      resize: vertical;
      font-family: inherit;
      color: #444;
    }

    .add-product-container input[type="text"]:focus,
    .add-product-container input[type="number"]:focus,
    .add-product-container textarea:focus {
      border-color: #ff7e5f;
      outline: none;
      box-shadow: 0 0 5px rgba(255,126,95,0.5);
    }

    /* Textarea height */
    .add-product-container textarea {
      min-height: 100px;
    }

    /* Submit button */
    .add-product-container button {
      width: 100%;
      background-color: #ff7e5f;
      color: white;
      font-weight: 700;
      padding: 0.85rem 0;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      cursor: pointer;
      margin-top: 1.5rem;
      transition: background-color 0.3s ease;
    }

    .add-product-container button:hover {
      background-color: #eb6a48;
    }

    /* Error message */
    .error-message {
      color: #d9534f;
      background: #f9d6d5;
      border-radius: 8px;
      padding: 0.7rem 1rem;
      margin-bottom: 1rem;
      font-weight: 600;
      text-align: center;
    }

    /* Responsive */
    @media (max-width: 520px) {
      .add-product-container {
        margin: 1.5rem 1rem;
        padding: 1.5rem 1.75rem;
      }
    }
  </style>
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

      <label for="image_url">Image URL</label>
      <input type="text" id="image_url" name="image_url" required />

      <button type="submit">Add Product</button>
    </form>
  </div>
</body>
</html>
