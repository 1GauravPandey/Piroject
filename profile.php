<?php
include 'db.php';
include 'session.php';

// Redirect if not logged in or is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

$userId = $_SESSION['id'];
$query = "SELECT name, email FROM users WHERE id = $userId LIMIT 1";
$result = mysqli_query($conn, $query);

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - Jutta Sansaar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">👤 My Profile</h2>
    <div class="card shadow-sm">
      <div class="card-body">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Joined On:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
        <a href="index.php" class="btn btn-secondary mt-3">← Back to Home</a>
        <a href="logout.php" class="btn btn-danger mt-3 float-end">Logout</a>
      </div>
    </div>
  </div>
</body>
</html>
