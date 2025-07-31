<?php
// Include database connection and session management
include 'db.php';
include 'session.php';

// Ensure only logged-in customers can access this page
require_customer();

// Get the currently logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Handle AJAX POST requests for cart operations (update/remove)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Update quantity of a product in the cart
    if ($action === 'update_quantity') {
        $product_id = (int)$_POST['product_id'];
        $quantity = max(1, (int)$_POST['quantity']); // Minimum quantity is 1
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;

    // Remove a product from the cart
    } elseif ($action === 'remove_item') {
        $product_id = (int)$_POST['product_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    }
}

// Fetch all cart items for the current user along with product info
$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Your Cart - Jutta Sansaar</title>
<link rel="stylesheet" href="cart.css">

</head>
<body>
<header>
    <div class="container">
      <h1><a href="index.php" style="text-decoration: none; color: inherit;">👟 Jutta Sansaar</a></h1>
      <nav id="nav-menu" aria-label="Primary">
      <a href="index.php" class="nav-link ">Home</a>
      <a href="products.php" class="nav-link" aria-current="page">Shop</a>
      <a href="cart.php" class="nav-link active">Cart</a>
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
  
<main>
<?php if (empty($cart_items)) : ?>
  <!-- Display when cart is empty -->
  <p class="empty-cart-message">
    Your cart is empty.<br>
    <a href="products.php">Go shopping now!</a>
  </p>
<?php else: ?>
  <!-- Display cart items in a table -->
  <div class="cart-container">
    <table aria-label="Shopping Cart">
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Total</th>
          <th>Remove</th>
        </tr>
      </thead>
      <tbody id="cart-body">
        <?php
        $subtotal = 0;
        foreach ($cart_items as $item) :
            $total_price = $item['price'] * $item['quantity'];
            $subtotal += $total_price;

            // Escape and format values for safety and presentation
            $productId = htmlspecialchars($item['id']);
            $productName = htmlspecialchars($item['name']);
            $productImage = htmlspecialchars($item['image']);
            $productPrice = number_format($item['price'], 2);
            $productQuantity = htmlspecialchars($item['quantity']);
            $productTotal = number_format($total_price, 2);
        ?>
        <tr data-product-id="<?= $productId ?>">
          <td><img src="images/<?= $productImage ?>" alt="<?= $productName ?>" /><div><?= $productName ?></div></td>
          <td><input type="number" min="1" class="quantity-input" value="<?= $productQuantity ?>" /></td>
          <td>रु<?= $productPrice ?></td>
          <td class="item-total">रु<?= $productTotal ?></td>
          <td><button class="remove-btn" title="Remove item">&times;</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <!-- Cart Summary -->
      <tfoot>
        <tr><td colspan="3" style="text-align:right">Subtotal:</td><td colspan="2" id="subtotal">रु<?= number_format($subtotal, 2) ?></td></tr>
        <tr><td colspan="3" style="text-align:right">Tax (10%):</td><td colspan="2" id="tax">रु<?= number_format($subtotal * 0.10, 2) ?></td></tr>
        <tr><td colspan="3" style="text-align:right">Grand Total:</td><td colspan="2" id="grand-total">रु<?= number_format($subtotal * 1.10, 2) ?></td></tr>
        <tr>
          <td colspan="5" style="text-align:right;">
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
<?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p>
    </footer>
<script>
  // Handle mobile navigation toggle
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

  // Execute once DOM is loaded
  document.addEventListener('DOMContentLoaded', () => {
    const cartBody = document.getElementById('cart-body');

    // Recalculate totals (subtotal, tax, grand total)
    function updateTotals() {
      let subtotal = 0;
      cartBody.querySelectorAll('tr').forEach(row => {
          const qtyInput = row.querySelector('.quantity-input');
          const priceText = row.querySelector('td[data-label="Price"]').textContent.replace(/[^\d.]/g, '');
          const totalCell = row.querySelector('.item-total');
          const qty = parseInt(qtyInput.value);
          const price = parseFloat(priceText);
          const total = qty * price;
          totalCell.textContent = `रु${total.toFixed(2)}`;
          subtotal += total;
      });
      document.getElementById('subtotal').textContent = `रु${subtotal.toFixed(2)}`;
      const tax = subtotal * 0.10;
      document.getElementById('tax').textContent = `रु${tax.toFixed(2)}`;
      document.getElementById('grand-total').textContent = `रु${(subtotal + tax).toFixed(2)}`;
    }

    // Helper for sending POST requests
    function ajaxPost(data) {
      return fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
      }).then(res => res.json());
    }

    // Quantity input change handler
    cartBody.querySelectorAll('.quantity-input').forEach(input => {
      input.addEventListener('change', async (e) => {
        let newQty = parseInt(e.target.value);
        if (isNaN(newQty) || newQty < 1) {
          e.target.value = 1;
          newQty = 1;
        }
        const row = e.target.closest('tr');
        const productId = row.getAttribute('data-product-id');
        try {
          const response = await ajaxPost({
            action: 'update_quantity',
            product_id: productId,
            quantity: newQty
          });
          if (response.success) {
            updateTotals();
          } else {
            alert('Failed to update quantity.');
          }
        } catch {
          alert('Network error while updating quantity.');
        }
      });
    });

    // Remove item from cart
    cartBody.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        if (!confirm('Remove this item from your cart?')) return;
        const row = e.target.closest('tr');
        const productId = row.getAttribute('data-product-id');
        try {
          const response = await ajaxPost({
            action: 'remove_item',
            product_id: productId
          });
          if (response.success) {
            row.remove();
            updateTotals();
            if (cartBody.children.length === 0) {
              location.reload(); // Reload to show empty cart message
            }
          } else {
            alert('Failed to remove item.');
          }
        } catch {
          alert('Network error while removing item.');
        }
      });
    });
  });
</script>
</body>
</html>

