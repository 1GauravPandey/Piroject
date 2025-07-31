<?php
// Include database connection and session management files
include 'db.php';
include 'session.php';

// Restrict access: Only logged-in customers can access this page
require_customer();

$message = ''; // For storing success or error messages
$user_id = $_SESSION['user_id'] ?? 0; // Get the user ID from session, fallback to 0 if not set

// If the request is a POST (form submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input values
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // 1. Fetch all items in the user's cart
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    // Check if cart is empty
    if (empty($cart_items)) {
        $message = "Your cart is empty!";
    } else {
        // Calculate subtotal by summing (price × quantity) of each item
        $total = 0;
        foreach ($cart_items as $item) {
            $stmtPrice = $conn->prepare("SELECT price FROM products WHERE id = ?");
            $stmtPrice->bind_param("i", $item['product_id']);
            $stmtPrice->execute();
            $resPrice = $stmtPrice->get_result()->fetch_assoc();
            $price = $resPrice['price'] ?? 0;
            $total += $price * $item['quantity'];
        }

        // Apply tax (e.g., 10%) to the total
        $tax_rate = 0.10;
        $tax_amount = $total * $tax_rate;
        $total_with_tax = $total + $tax_amount;

        // 2. Insert the order into the `orders` table
        $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, name, phone, address, payment_method, status, total) VALUES (?, ?, ?, ?, ?, 'Pending', ?)");
        $stmtOrder->bind_param("issssd", $user_id, $name, $phone, $address, $payment_method, $total_with_tax);

        // Execute and check for success
        if ($stmtOrder->execute()) {
            $message = "Order placed successfully!";
            // You can add logic here to clear the cart or insert order items
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

  <!-- External CSS for layout and styling -->
  <link rel="stylesheet" href="checkout.css">
</head>
<body>
  <!-- HEADER SECTION -->
  <header>
    <div class="container">
      <h1><a href="index.php" style="text-decoration: none; color: inherit;">👟 Jutta Sansaar</a></h1>
      <nav id="nav-menu" aria-label="Primary">
        <!-- Navigation links -->
        <a href="index.php" class="nav-link">Home</a>
        <a href="products.php" class="nav-link" aria-current="page">Shop</a>
        <a href="cart.php" class="nav-link">Cart</a>
        <a href="checkout.php" class="nav-link active">Checkout</a>

        <!-- Show login/logout based on session -->
        <?php if (!is_logged_in()): ?>
          <a href="login.php">Login</a>
        <?php else: ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- MAIN CHECKOUT FORM -->
  <main class="checkout-container">
    <form method="POST" class="checkout-form">
      <h2>Checkout</h2>

      <!-- Show success or error message -->
      <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>

      <!-- Form input: Full Name -->
      <div class="form-group">
        <label for="name">Full Name</label>
        <input name="name" id="name" required />
      </div>

      <!-- Form input: Phone -->
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input name="phone" id="phone" required />
      </div>

      <!-- Form input: Address -->
      <div class="form-group">
        <label for="address">Delivery Address</label>
        <textarea name="address" id="address" rows="3" required></textarea>
      </div>

      <!-- Form input: Payment method -->
      <div class="form-group">
        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
          <option value="Cash on Delivery">Cash on Delivery</option>
          <!-- Add other payment methods as needed -->
        </select>
      </div>

      <!-- Submit button -->
      <button type="submit" class="btn">Place Order</button>
    </form>
  </main>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p>
  </footer>

  <!-- SCRIPT: Mobile navigation toggle -->
  <script>
    const menuIcon = document.getElementById('menu-icon');
    const navMenu = document.getElementById('nav-menu');
    menuIcon?.addEventListener('click', () => {
      navMenu.classList.toggle('show');
    });
  </script>
</body>
</html>
