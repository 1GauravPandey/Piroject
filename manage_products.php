<?php
// Include database connection
include 'db.php';

// Include session management to access session variables
include 'session.php';

// Check if user is logged in and is an admin; otherwise redirect to login page
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

// Fetch all products from the database
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Products</title>
  <!-- Bootstrap CSS for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Manage Products</h2>

  <!-- Button to navigate to add new product page -->
  <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>

  <!-- Products table -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th> <!-- Serial number, not DB product id -->
        <th>Name</th>
        <th>Price (Rs.)</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Image</th>
        <th>Actions</th> <!-- Edit and Delete buttons -->
      </tr>
    </thead>
    <tbody>
    <?php 
      // Initialize a counter for display purposes (serial number)
      $counter = 1;

      // Loop through each product record fetched from DB
      while ($row = mysqli_fetch_assoc($products)) : 
    ?>
    <tr>
      <td><?php echo $counter++; ?></td> <!-- Display serial number -->
      <td><?php echo htmlspecialchars($row['name']); ?></td> <!-- Escape output to prevent XSS -->
      <td><?php echo number_format($row['price'], 2); ?></td> <!-- Format price to 2 decimals -->
      <td><?php echo $row['stock']; ?></td> <!-- Display stock quantity -->
      <td><?php echo htmlspecialchars($row['category']); ?></td> <!-- Escape category -->
      <td>
        <!-- Show product image with fixed width/height -->
        <img src="images/<?php echo $row['image']; ?>" width="60" height="60" alt="">
      </td>
      <td>
        <!-- Edit button links to edit page with product id -->
        <a href="edit_products.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
        <!-- Delete button links to delete page with product id and confirmation prompt -->
        <a href="delete_products.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>

  </table>
</div>

<!-- Bootstrap JS bundle for components -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
