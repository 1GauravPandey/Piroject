<?php
// Include the database connection and session validation
include 'db.php';
include 'session.php';

// Custom function to ensure the user is logged in as a customer
require_customer();

// Get the current logged-in user's ID from session
$user_id = $_SESSION['user_id'];

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get product ID and quantity from the POST request
    $product_id = (int) $_POST['product_id'];
    $quantity = max(1, (int) $_POST['quantity']); // Ensure quantity is at least 1

    // Step 1: Check if the product already exists in the user's cart
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id); // Bind parameters securely
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Step 2: If product exists, update the quantity
        $new_quantity = $row['quantity'] + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $update->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update->execute();
    } else {
        // Step 3: If product doesn't exist in cart, insert a new row
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
    }

    // Redirect the user to the cart page after adding the product
    header("Location: cart.php");
    exit;

} else {
    // If the page was accessed without a POST request, show an error message
    echo "<p style='font-family: sans-serif; text-align: center; margin-top: 2rem;'>
            Invalid access. Please return to the 
            <a href='products.php'>Products Page</a>.
          </p>";
}
?>
