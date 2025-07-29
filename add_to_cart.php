<?php
include 'db.php';

include 'session.php';

// Simulated user login (replace with actual session logic)
$user_id = $_SESSION['user_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int) $_POST['product_id'];
    $quantity = max(1, (int) $_POST['quantity']);

    // Check if product already in cart
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Update quantity
        $new_quantity = $row['quantity'] + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $update->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update->execute();
    } else {
        // Insert new item
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
    }

    header("Location: cart.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
