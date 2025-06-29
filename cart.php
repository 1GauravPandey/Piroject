<?php
include 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 1; // default to user 1 for demo

// Handle AJAX requests for update and delete
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

// Fetch cart items with product details
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
<!-- <link rel="stylesheet" href="styles.css" /> -->
<style>
/* Minimal styling for cart - you can extend this in styles.css */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f4f4;
    margin: 0; padding: 20px;
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
h1 {
    margin: 0;
    color: #333;
}
a.button {
    background: #007bff;
    color: white;
    padding: 0.5em 1em;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
}
a.button:hover {
    background: #0056b3;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
}
th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
th {
    background: #007bff;
    color: white;
}
td img {
    width: 60px;
    height: auto;
    border-radius: 4px;
}
.quantity-input {
    width: 60px;
    padding: 5px;
    font-size: 1rem;
    border-radius: 4px;
    border: 1px solid #ccc;
}
.remove-btn {
    background: #dc3545;
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
}
.remove-btn:hover {
    background: #b52a37;
}
tfoot td {
    font-weight: bold;
    font-size: 1.1em;
}
tfoot tr td:first-child {
    text-align: right;
}
.checkout-btn {
    margin-top: 20px;
    padding: 10px 20px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
}
.checkout-btn:hover {
    background: #1e7e34;
}
@media (max-width: 600px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }
    thead tr {
        display: none;
    }
    td {
        border: none;
        position: relative;
        padding-left: 50%;
        margin-bottom: 1rem;
    }
    td::before {
        position: absolute;
        top: 12px;
        left: 15px;
        width: 45%;
        white-space: nowrap;
        font-weight: bold;
        content: attr(data-label);
    }
    td img {
        width: 100%;
        max-width: 150px;
        margin-bottom: 10px;
    }
}
</style>
</head>
<body>
<header>
    <h1>Your Cart</h1>
    <a href="products.php" class="button">Continue Shopping</a>
</header>
<main>
    <?php if (empty($cart_items)) : ?>
        <p>Your cart is empty. <a href="products.php">Go shopping now!</a></p>
    <?php else: ?>
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
            ?>
            <tr data-product-id="<?= htmlspecialchars($item['id']) ?>">
                <td data-label="Product">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                    <div><?= htmlspecialchars($item['name']) ?></div>
                </td>
                <td data-label="Qty">
                    <input type="number" min="1" class="quantity-input" value="<?= htmlspecialchars($item['quantity']) ?>" />
                </td>
                <td data-label="Price">$<?= number_format($item['price'], 2) ?></td>
                <td data-label="Total" class="item-total">$<?= number_format($total_price, 2) ?></td>
                <td data-label="Remove">
                    <button class="remove-btn" title="Remove item">&times;</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right">Subtotal:</td>
                <td id="subtotal" colspan="2">$<?= number_format($subtotal, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right">Tax (10%):</td>
                <td id="tax" colspan="2">$<?= number_format($subtotal * 0.10, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right">Grand Total:</td>
                <td id="grand-total" colspan="2">$<?= number_format($subtotal * 1.10, 2) ?></td>
            </tr>
        </tfoot>
    </table>
    <button class="checkout-btn" onclick="alert('Checkout not implemented')">Proceed to Checkout</button>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cartBody = document.getElementById('cart-body');

    function updateTotals() {
        let subtotal = 0;
        cartBody.querySelectorAll('tr').forEach(row => {
            const qtyInput = row.querySelector('.quantity-input');
            const priceText = row.querySelector('td[data-label="Price"]').textContent.replace('$','');
            const totalCell = row.querySelector('.item-total');

            const qty = parseInt(qtyInput.value);
            const price = parseFloat(priceText);
            const total = qty * price;
            totalCell.textContent = `$${total.toFixed(2)}`;
            subtotal += total;
        });

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        const tax = subtotal * 0.10;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('grand-total').textContent = `$${(subtotal + tax).toFixed(2)}`;
    }

    function ajaxPost(data) {
        return fetch('cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams(data)
        }).then(res => res.json());
    }

    // Quantity change handler
    cartBody.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', async (e) => {
            let newQty = parseInt(e.target.value);
            if (isNaN(newQty) || newQty < 1) {
                e.target.value = 1;
                newQty = 1;
            }
            const row = e.target.closest('tr');
            const productId = row.getAttribute('data-product-id');

            // Send update to server
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
        });
    });

    // Remove item handler
    cartBody.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            if (!confirm('Remove this item from your cart?')) return;
            const row = e.target.closest('tr');
            const productId = row.getAttribute('data-product-id');

            const response = await ajaxPost({
                action: 'remove_item',
                product_id: productId
            });

            if (response.success) {
                row.remove();
                updateTotals();

                // If cart empty, reload page to show empty message
                if (cartBody.children.length === 0) {
                    location.reload();
                }
            } else {
                alert('Failed to remove item.');
            }
        });
    });
});
</script>

</body>
</html>
