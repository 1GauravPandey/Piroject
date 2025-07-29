<?php
include 'db.php';

include 'session.php';

$user_id = $_SESSION['user_id'] ?? 1; // Default demo user

// Handle AJAX POST requests for cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'update_quantity') {
        $product_id = (int)$_POST['product_id'];
        $quantity = max(1, (int)$_POST['quantity']);
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    } elseif ($action === 'remove_item') {
        $product_id = (int)$_POST['product_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    }
}

// Fetch cart items with product details for display
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
<!-- <link rel="stylesheet" href="style.css"> -->
<style>
    * {
      box-sizing: border-box;
      margin: 0; padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
    a{
        text-decoration:none;
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
main {
  flex: 1;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
td {
  padding: 16px;
  text-align: center;
  border-bottom: 1px solid #f0f0f0;
  font-size: 0.95rem;
}
th {
  background: linear-gradient(to right, #007bff, #00c6ff);
  color: #fff;
  padding: 16px;
  font-size: 1rem;
  font-weight: bold;
  text-align: center;
}
td img {
  height: 60px;
  margin-bottom: 8px;
  border-radius: 6px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  width: 60px;
  object-fit: cover;
  background: #eee;
  display: inline-block;
}
tfoot td {
  font-weight: bold;
  text-align: right;
  padding-right: 24px;
  background-color: #f9f9f9;
  font-size: 1rem;
}
input[type="number"] {
  width: 50px;
  padding: 6px;
  font-size: 1rem;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 6px;
}
.quantity-input {
    width: 60px;
    padding: 5px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.remove-btn {
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  font-size: 1rem;
  line-height: 1;
  cursor: pointer;
  transition: background 0.3s ease;
}
.remove-btn:hover {
  background-color: #c0392b;
}
.checkout-btn {
  display: inline-block;
  background: #28a745;
  color: #fff;
  padding: 10px 20px;
  margin-top: 12px;
  text-decoration: none;
  border-radius: 6px;
  transition: background 0.3s ease;
}
.checkout-btn:hover {
  background: #218838;
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
  .cart-container {
  max-width: 1000px;
  margin: 3rem auto;
  padding: 1rem;
  background-color: #f0f8ff;
  border-radius: 12px;
}
  .empty-cart-message {
  text-align: center;
  font-size: 1.5rem;
  padding: 4rem 2rem;
  color: #fff;
  text-shadow: 1px 1px 4px rgba(0,0,0,0.6);
}
.empty-cart-message a {
  display: inline-block;
  margin-top: 1rem;
  color: #f39c12;
  font-weight: bold;
  text-decoration: none;
  border: 2px solid #f39c12;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  transition: 0.3s;
}
.empty-cart-message a:hover {
  background-color: #f39c12;
  color: #222;
}

</style>
</head>
<body>
<header>
    <div class="container">
      <h1>👟 Jutta Sansaar</h1>
      <nav id="nav-menu" aria-label="Primary">
      <a href="index.php" class="nav-link ">Home</a>
      <a href="products.php" class="nav-link" aria-current="page">Shop</a>
      <a href="cart.php" class="nav-link active">Cart</a>
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
  
<main>
</div>
    <?php if (empty($cart_items)) : ?>
        <p class="empty-cart-message">
  Your cart is empty.<br>
  <a href="products.php">Go shopping now!</a></p>
    <?php else: ?>
       <div class="cart-container">
    <table aria-label="Shopping Cart">
      <!-- your cart rows -->
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
                $productId = htmlspecialchars($item['id']);
                $productName = htmlspecialchars($item['name']);
                $productImage = htmlspecialchars($item['image']);
                $productPrice = number_format($item['price'], 2);
                $productQuantity = htmlspecialchars($item['quantity']);
                $productTotal = number_format($total_price, 2);
            ?>
            <tr data-product-id="<?= $productId ?>">
                <td data-label="Product">
                    <img src="images/<?= $productImage ?>" alt="<?= $productName ?>" />
                    <div><?= $productName ?></div>
                </td>
                <td data-label="Qty">
                    <input type="number" min="1" class="quantity-input" value="<?= $productQuantity ?>" />
                </td>
                <td data-label="Price">रु<?= $productPrice ?></td>
                <td data-label="Total" class="item-total">रु<?= $productTotal ?></td>
                <td data-label="Remove">
                    <button class="remove-btn" title="Remove item">&times;</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right">Subtotal:</td>
                <td id="subtotal" colspan="2">रु<?= number_format($subtotal, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right">Tax (10%):</td>
                <td id="tax" colspan="2">रु<?= number_format($subtotal * 0.10, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right">Grand Total:</td>
                <td id="grand-total" colspan="2">रु<?= number_format($subtotal * 1.10, 2) ?></td>
            </tr>
            <tr>
            <td colspan="5" style="text-align: right;">
              <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </td>
          </tr>
        </tfoot>
    </table>
    <?php endif; ?>
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

document.addEventListener('DOMContentLoaded', () => {
    const cartBody = document.getElementById('cart-body');

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


    function ajaxPost(data) {
        return fetch('cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams(data)
        }).then(res => res.json());
    }

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
                        location.reload();
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
