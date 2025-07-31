<?php
// Include database connection
include 'db.php';

// Include session handling and access control
include 'session.php';

// Only allow access if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

// Get the product ID from the URL query string
$id = $_GET['id'] ?? 0;

// Fetch the existing product data from the database
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));

// If no product is found with the given ID, display error and exit
if (!$product) {
    echo "Product not found.";
    exit();
}

// Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Check if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        // Save uploaded image with original name into "images/" directory
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    } else {
        // Keep existing image if none was uploaded
        $image = $product['image'];
    }

    // Update product record in the database
    $update = "UPDATE products SET 
                name='$name', 
                price=$price, 
                stock=$stock, 
                category='$category', 
                image='$image' 
              WHERE id=$id";

    mysqli_query($conn, $update);

    // Redirect back to product management page after update
    header("Location: manage_products.php");
    exit();
}
?>

<!-- HTML FORM for editing product -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <!-- Product Name Field -->
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
        </div>

        <!-- Product Price Field -->
        <div class="mb-3">
            <label>Price (Rs.)</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="form-control" required>
        </div>

        <!-- Product Stock Field -->
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" value="<?= $product['stock'] ?>" class="form-control" required>
        </div>

        <!-- Product Category Field -->
        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" class="form-control">
        </div>

        <!-- Product Image Upload -->
        <div class="mb-3">
            <label>Current Image</label><br>
            <img src="images/<?= $product['image'] ?>" width="80" height="80"><br><br>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Submit and Cancel Buttons -->
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
