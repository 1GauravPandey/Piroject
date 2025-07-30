<?php
include 'db.php';
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Jutta Sansaar - Shop Shoes</title>
  <link rel="stylesheet" href="css/products.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <div class="container">
      <h1>👟 Jutta Sansaar</h1>
      <nav id="nav-menu" aria-label="Primary">
        <a href="index.php" class="nav-link">Home</a>
        <a href="products.php" class="nav-link active" aria-current="page">Shop</a>
        <a href="cart.php" class="nav-link">Cart</a>
        <a href="checkout.php" class="nav-link">Checkout</a>
        <?php if (!is_logged_in()): ?>
          <a href="login.php">Login</a>
        <?php else: ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </nav>
      <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
        <span></span><span></span><span></span>
      </div>
    </div>
  </header>

  <main class="featured">
    <h2>Shop by Category</h2>
    <select id="category-filter">
      <option value="all">All Categories</option>
      <?php
      // Dynamically list available categories
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
    // Fallback if no products in DB
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

    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 0) {
      foreach ($fallback as $item) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $item['name'], $item['price'], $item['image'], $item['category']);
        $stmt->execute();
      }
      $result = mysqli_query($conn, $sql); // Re-fetch after insert
    }

    while ($row = mysqli_fetch_assoc($result)) {
      $id = htmlspecialchars($row['id']);
      $name = htmlspecialchars($row['name']);
      $price = number_format($row['price'], 2);
      $category = htmlspecialchars($row['category'] ?? 'Uncategorized');
      $image = htmlspecialchars($row['image']);
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
    // Hamburger toggle
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

    // Category filter logic
    document.getElementById('category-filter').addEventListener('change', function () {
      const selected = this.value.toLowerCase();
      const products = document.querySelectorAll('.product');
      products.forEach(product => {
        const category = product.getAttribute('data-category')?.toLowerCase() || 'uncategorized';
        if (selected === 'all' || selected === category) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
