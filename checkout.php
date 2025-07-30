<?php
include 'db.php';
include 'session.php';
require_customer();

$message = '';
$user_id = $_SESSION['user_id'];

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
  <link rel="stylesheet" href="checkout.css">
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
      <!-- Show Login if not logged in, otherwise Logout -->
    <?php if (!is_logged_in()): ?>
      <a href="login.php">Login</a>
    <?php else: ?>
      <a href="logout.php">Logout</a>
    <?php endif; ?>
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
