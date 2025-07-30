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
  <link rel="stylesheet" href="products.css"/>
</head>
<body>
    <header>
    <div class="container">
      <h1>👟 Jutta Sansaar</h1>
      <nav id="nav-menu" aria-label="Primary">
      <a href="index.php" class="nav-link ">Home</a>
      <a href="products.php" class="nav-link active" aria-current="page">Shop</a>
      <a href="cart.php" class="nav-link">Cart</a>
      <a href="checkout.php" class="nav-link">Checkout</a>
      <!-- Show Login if not logged in, otherwise Logout -->
    <?php if (!is_logged_in()): ?>
      <a href="login.php">Login</a>
    <?php else: ?>
      <a href="logout.php">Logout</a>
    <?php endif; ?>
      </nav>
      <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </header>

  <main class="featured">
    <?php
    // Fallback sample products
    $fallback = [
      ['name' => 'Black Sports Shoes', 'price' => 1999, 'image' => 'black_sports.jpg'],
      ['name' => 'Classic Leather Loafers', 'price' => 2499, 'image' => 'loafers.jpg'],
      ['name' => 'Casual White Sneakers', 'price' => 1799, 'image' => 'sneakers.jpg'],
      ['name' => 'High-Top Basketball Shoes', 'price' => 2899, 'image' => 'https://i5.walmartimages.com/seo/Kid-s-Basketball-Shoes-Boys-Sneakers-Girls-Trainers-Comfort-High-Top-Basketball-Shoes-for-Boys-Little-Kid-Big-Kid-White-Black_997ad829-1480-4baa-b184-98851ab7935a.4dc81d532dbb9b0b935c839a988458f8.jpeg'],
      ['name' => 'Running Shoes - Red', 'price' => 2099, 'image' => 'running_red.jpg'],
      ['name' => 'Trail Hiking Boots', 'price' => 3199, 'image' => 'trail_boots.jpg'],
      ['name' => 'Slip-On Canvas Shoes', 'price' => 1599, 'image' => 'slipon_canvas.jpg'],
      ['name' => 'Formal Oxford Shoes', 'price' => 2999, 'image' => 'oxford.jpg'],
      ['name' => 'Kids Light-Up Shoes', 'price' => 1899, 'image' => 'kids_lightup.jpg'],
      ['name' => 'Chunky Dad Sneakers', 'price' => 2699, 'image' => 'dad_sneakers.jpg'],
      ['name' => 'Neon Green Trainers', 'price' => 2199, 'image' => 'neon_trainers.jpg'],
      ['name' => 'Limited Edition Gold High-Tops', 'price' => 3499, 'image' => 'gold_hightops.jpg'],
      ['name' => 'Winter Fur-lined Boots', 'price' => 2799, 'image' => 'fur_boots.jpg'],
      ['name' => 'Summer Flip Flops', 'price' => 899, 'image' => 'flipflops.jpg']
    ];

    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 0) {
      foreach ($fallback as $item) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $item['name'], $item['price'], $item['image']);
        $stmt->execute();
      }
      $result = mysqli_query($conn, $sql); // re-fetch
    }

    while ($row = mysqli_fetch_assoc($result)) {
      $id = htmlspecialchars($row['id']);
      $name = htmlspecialchars($row['name']);
      $price = number_format($row['price'], 2);
      $image = htmlspecialchars($row['image']);

      // Check if image is URL or local
      $imgSrc = filter_var($image, FILTER_VALIDATE_URL) ? $image : "images/{$image}";

      echo "<div class='product'>
              <span class='badge'>New</span>
              <img src='{$imgSrc}' alt='{$name}'>
              <h4>{$name}</h4>
              <p>रु{$price}</p>
              <form method='POST' action='add_to_cart.php'>
                <input type='hidden' name='product_id' value='{$id}'>
                <input type='number' name='quantity' value='1' min='1'>
                <br/>
                <button type='submit'>🛒 Add to Cart</button>
              </form>
            </div>";
    }
    ?>
   
  </main>

    <footer>
    <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p>
    </footer>

    <script>
    // Hamburger menu toggle
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
    </script>
</body>
</html>
