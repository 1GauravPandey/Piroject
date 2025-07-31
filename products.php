<?php
// Include database connection and session management
include 'db.php';
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Jutta Sansaar - Shop Shoes</title>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="products.css"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <div class="container">
      <!-- Site title linking back home -->
      <h1><a href="index.php" style="text-decoration: none; color: inherit;">👟 Jutta Sansaar</a></h1>
      <!-- Navigation menu -->
      <nav id="nav-menu" aria-label="Primary">
        <a href="index.php" class="nav-link">Home</a>
        <a href="products.php" class="nav-link active" aria-current="page">Shop</a>
        <a href="cart.php" class="nav-link">Cart</a>
        <a href="checkout.php" class="nav-link">Checkout</a>
        <!-- Show login or logout based on user session -->
        <?php if (!is_logged_in()): ?>
          <a href="login.php">Login</a>
        <?php else: ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </nav>
      <!-- Hamburger menu icon for mobile -->
      <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
        <span></span><span></span><span></span>
      </div>
    </div>
  </header>

  <main class="featured">
    <h2>Shop by Category</h2>
    <!-- Category dropdown filter -->
    <select id="category-filter">
      <option value="all">All Categories</option>
      <?php
      // Query distinct categories from products table
      $cat_query = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''";
      $cat_result = mysqli_query($conn, $cat_query);
      while ($cat_row = mysqli_fetch_assoc($cat_result)) {
        $cat = htmlspecialchars($cat_row['category']);
        echo "<option value='{$cat}'>{$cat}</option>";
      }
      ?>
    </select>

    <div class="product-grid">
    <?php
    // Fallback products to insert if DB is empty
    $fallback = [
      ['name' => 'Black Sports Shoes', 'price' => 1999, 'image' => 'black_sports.jpg', 'category' => 'Men'],
      ['name' => 'Classic Leather Loafers', 'price' => 2499, 'image' => 'loafers.jpg', 'category' => 'Men'],
      ['name' => 'Casual White Sneakers', 'price' => 1799, 'image' => 'sneakers.jpg', 'category' => 'Women'],
      ['name' => 'High-Top Basketball Shoes', 'price' => 2899, 'image' => 'basketball.jpg', 'category' => 'Sports'],
      ['name' => 'Running Shoes - Red', 'price' => 2099, 'image' => 'running_red.jpg', 'category' => 'Sports'],
      ['name' => 'Trail Hiking Boots', 'price' => 3199, 'image' => 'trail_boots.jpg', 'category' => 'Outdoor'],
      ['name' => 'Slip-On Canvas Shoes', 'price' => 1599, 'image' => 'slipon_canvas.jpg', 'category' => 'Casual'],
      ['name' => 'Formal Oxford Shoes', 'price' => 2999, 'image' => 'oxford.jpg', 'category' => 'Formal'],
      ['name' => 'Kids Light-Up Shoes', 'price' => 1899, 'image' => 'kids_lightup.jpg', 'category' => 'Kids'],
      ['name' => 'Chunky Dad Sneakers', 'price' => 2699, 'image' => 'dad_sneakers.jpg', 'category' => 'Trendy'],
      ['name' => 'Neon Green Trainers', 'price' => 2199, 'image' => 'neon_trainers.jpg', 'category' => 'Sports'],
      ['name' => 'Limited Edition Gold High-Tops', 'price' => 3499, 'image' => 'gold_hightops.jpg', 'category' => 'Limited'],
      ['name' => 'Winter Fur-lined Boots', 'price' => 2799, 'image' => 'fur_boots.jpg', 'category' => 'Winter'],
      ['name' => 'Summer Flip Flops', 'price' => 899, 'image' => 'flipflops.jpg', 'category' => 'Summer']
    ];

    // Query all products
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    // If no products found in DB, insert fallback products
    if (mysqli_num_rows($result) === 0) {
      foreach ($fallback as $item) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $item['name'], $item['price'], $item['image'], $item['category']);
        $stmt->execute();
      }
      // Re-query products after inserting fallback
      $result = mysqli_query($conn, $sql);
    }

    // Loop through all products and output the product card HTML
    while ($row = mysqli_fetch_assoc($result)) {
      $id = htmlspecialchars($row['id']);
      $name = htmlspecialchars($row['name']);
      $price = number_format($row['price'], 2);
      $category = htmlspecialchars($row['category'] ?? 'Uncategorized');
      $image = htmlspecialchars($row['image']);
      // Check if image is a valid URL, else prepend folder path
      $imgSrc = filter_var($image, FILTER_VALIDATE_URL) ? $image : "images/{$image}";

      echo "<div class='product' data-category='{$category}'>
              <span class='badge'>New</span>
              <img src='{$imgSrc}' alt='{$name}'>
              <h4>{$name}</h4>
              <p>रु{$price}</p>
              <form method='POST' action='add_to_cart.php'>
                <input type='hidden' name='product_id' value='{$id}'>
                <input type='number' name='quantity' value='1' min='1'><br/>
                <button type='submit'>🛒 Add to Cart</button>
              </form>
            </div>";
    }
    ?>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p>
  </footer>

  <script>
    // Hamburger menu toggle for mobile navigation
    const menuIcon = document.getElementById('menu-icon');
    const navMenu = document.getElementById('nav-menu');
    menuIcon.addEventListener('click', () => {
      navMenu.classList.toggle('show');
    });
    menuIcon.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        navMenu.classList.toggle('show');
      }
    });

    // Category filter dropdown change handler
    document.getElementById('category-filter').addEventListener('change', function () {
      const selected = this.value.toLowerCase();
      const products = document.querySelectorAll('.product');
      products.forEach(product => {
        const category = product.getAttribute('data-category')?.toLowerCase() || 'uncategorized';
        if (selected === 'all' || selected === category) {
          product.style.display = 'block';  // Show products matching filter
        } else {
          product.style.display = 'none';   // Hide others
        }
      });
    });
  </script>
</body>
</html>
