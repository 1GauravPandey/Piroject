<?php
include 'db.php';
include 'session.php';
require_customer();

$message = '';
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // 1. Fetch user's cart items
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    if (empty($cart_items)) {
        $message = "Your cart is empty!";
    } else {
        // Calculate subtotal and tax here (replace your old total calc)
        $total = 0;
        foreach ($cart_items as $item) {
            $stmtPrice = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmtPrice->bind_param("i", $item['product_id']);
            $stmtPrice->execute();
            $resPrice = $stmtPrice->get_result()->fetch_assoc();
            $price = $resPrice['price'] ?? 0;
            $total += $price * $item['quantity'];
        }

        // Calculate tax and final total
        $tax_rate = 0.10; // 10%
        $tax_amount = $total * $tax_rate;
        $total_with_tax = $total + $tax_amount;

        // Insert order with total including tax
        $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, name, phone, address, payment_method, status, total) VALUES (?, ?, ?, ?, ?, 'Pending', ?)");
        $stmtOrder->bind_param("issssd", $user_id, $name, $phone, $address, $payment_method, $total_with_tax);

        if ($stmtOrder->execute()) {
            // Rest of your order insertion logic...
        } else {
            $message = "Error placing order: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout - Jutta Sansaar</title>
  <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
  <header>
    <div class="container">
      <h1>👟 Jutta Sansaar</h1>
      <nav id="nav-menu" aria-label="Primary">
        <a href="index.php" class="nav-link ">Home</a>
        <a href="products.php" class="nav-link" aria-current="page">Shop</a>
        <a href="cart.php" class="nav-link">Cart</a>
        <a href="checkout.php" class="nav-link active">Checkout</a>
        <?php if (!is_logged_in()): ?>
          <a href="login.php">Login</a>
        <?php else: ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="checkout-container">
    <form method="POST" class="checkout-form">
      <h2>Checkout</h2>
      <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
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
          <!-- Add more methods if needed -->
        </select>
      </div>

      <button type="submit" class="btn">Place Order</button>
    </form>
  </main>

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
