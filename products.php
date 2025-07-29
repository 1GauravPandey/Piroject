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
  <!-- <link rel="stylesheet" href="style.css"/> -->
  <style>
     * {
      box-sizing: border-box;
      margin: 0; padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      /* background-color: #f2f3f7; */
      background: url('https://cloudfront-us-east-2.images.arcpublishing.com/reuters/NKYZ3DCPBRMHVL3LP65XEHONXA.jpg') no-repeat center center/cover ;
      /* background: #fff; */
      color: #222;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

   header {
      background: #222;
      color: white;
      padding: 1rem 2rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    header h1 {
      font-size: 1.8rem;
      letter-spacing: 2px;
      user-select: none;
    }
    nav {
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }
    nav a {
      font-weight: 600;
      color: #ddd;
      padding: 0.5rem 0.75rem;
      border-radius: 4px;
      transition: background 0.3s, color 0.3s;
    }
    nav a:hover, nav a.active {
      background: #f39c12;
      color: #222;
    }

    /* FOOTER */
    footer {
      background: #222;
      color: white;
      text-align: center;
      padding: 1.5rem 2rem;
      margin-bottom: auto;
      font-size: 0.9rem;
      letter-spacing: 1px;
      user-select: none;
    }
    
    main.featured {
      flex-grow: 1;
      display: grid;
      grid-template-columns: no-repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 2.5rem;
      align-content: start; /* ✅ This keeps cards at natural height */
    }
    .featured {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 2.5rem;
    }

    .product {
      background: white;
      border-radius: 15px;
      padding: 1.2rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s;
      position: relative;
    }

    .product:hover {
      transform: scale(1.03);
    }

    .product img {
      width: 100%;
      height: 220px;
      object-fit: contain;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .product h4 {
      margin: 0.5rem 0;
      font-size: 1.2rem;
      color: #333;
    }

    .product p {
      color: #2ecc71;
      font-weight: bold;
      font-size: 1.1rem;
      margin: 0.3rem 0 1rem;
    }

    .product input[type='number'] {
      width: 60px;
      padding: 6px;
      margin-bottom: 0.7rem;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .product button {
      background: #ff4500;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .product button:hover {
      background: #e03e00;
    }

    .badge {
      position: absolute;
      top: 15px;
      left: 15px;
      background: #ff4757;
      color: #fff;
      font-size: 0.8rem;
      padding: 5px 10px;
      border-radius: 20px;
      font-weight: bold;
    }

    /* Hamburger menu */
    #menu-toggle {
      display: none;
    }
    .menu-icon {
      display: none;
      cursor: pointer;
      flex-direction: column;
      gap: 5px;
      width: 25px;
    }
    .menu-icon span {
      display: block;
      height: 3px;
      background: white;
      border-radius: 3px;
      transition: 0.3s;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      nav {
        gap: 1rem;
      }
    }
    @media (max-width: 600px) {
      header {
        padding: 1rem;
      }
      nav {
        display: none;
        flex-direction: column;
        background: #222;
        position: absolute;
        top: 100%;
        right: 0;
        width: 200px;
        border-radius: 0 0 0 10px;
      }
      nav.show {
        display: flex;
      }
      .menu-icon {
        display: flex;
      }
    }
  </style>
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
      <a href="login.php" class="nav-link">Login</a>
      <a href="logout.php">Logout</a>
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
