<?php
include 'db.php';

include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}


$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Manage Products</h2>

  <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price (Rs.)</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($products)) : ?>

      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo number_format($row['price'], 2); ?></td>
        <td><?php echo $row['stock']; ?></td>
        <td><?php echo htmlspecialchars($row['category']); ?></td>
        <td><img src="uploads/<?php echo $row['image']; ?>" width="60" height="60" alt=""></td>
        <td>
          <a href="edit_products.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="delete_products.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>

      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
