<?php
include 'db.php';

include 'session.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $payment_method = $_POST['payment_method'];
  $user_id = $_SESSION['user_id'] ?? 0;

  $sql = "INSERT INTO orders (user_id, name, phone, address, payment_method, status) 
          VALUES ('$user_id', '$name', '$phone', '$address', '$payment_method', 'Pending')";
  if (mysqli_query($conn, $sql)) {
    $message = "Order placed successfully!";
  } else {
    $message = "Error placing order: " . mysqli_error($conn);
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout - Jutta Sansaar</title>
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
    .checkout-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 3rem 1rem;
    }

    .checkout-form {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    .checkout-form h2 {
      text-align: center;
      margin-bottom: 1rem;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #333;
    }

    .form-group input, .form-group textarea, .form-group select {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .btn {
      width: 100%;
      background-color: #ff7e5f;
      color: white;
      border: none;
      padding: 0.9rem;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #eb6a48;
    }

    .message {
      text-align: center;
      color: green;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <h1>👟 Jutta Sansaar</h1>
      <nav>
        <nav id="nav-menu" aria-label="Primary">
      <a href="index.php" class="nav-link ">Home</a>
      <a href="products.php" class="nav-link" aria-current="page">Shop</a>
      <a href="cart.php" class="nav-link">Cart</a>
      <a href="checkout.php" class="nav-link active">Checkout</a>
      <a href="login.php" class="nav-link">Login</a>
      <a href="logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <div class="checkout-container">
    <form method="POST" class="checkout-form">
      <h2>Checkout</h2>
      <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
      <?php endif; ?>

      <div class="form-group">
        <label for="name">Full Name</label>
        <input name="name" id="name" required />
      </div>

      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input name="phone" id="phone" required />
      </div>

      <div class="form-group">
        <label for="address">Delivery Address</label>
        <textarea name="address" id="address" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
          <option value="Cash on Delivery">Cash on Delivery</option>
        </select>
      </div>

      <button type="submit" class="btn">Place Order</button>
    </form>
  </div>

  <footer>
    <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p>
  </footer>

<script>
  // Hamburger menu toggle
  const menuIcon = document.getElementById('menu-icon');
  const navMenu = document.getElementById('nav-menu');
  menuIcon?.addEventListener('click', () => {
    navMenu.classList.toggle('show');
  });
</script>

</body>
</html>
