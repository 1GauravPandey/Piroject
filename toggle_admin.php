<?php
include 'db.php';
include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int) $_POST['user_id'];
    $is_admin = (int) $_POST['is_admin'];

    // Prevent changing your own admin status
    if ($user_id === $_SESSION['user_id']) {
        header("Location: users.php?error=selfchange");
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_admin, $user_id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}
?>
