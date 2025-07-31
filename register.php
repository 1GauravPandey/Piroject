<!-- register.php -->
<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (name, email, password, is_admin) VALUES ('$name', '$email', '$password', 0)";
  if (mysqli_query($conn, $sql)) {
    header("Location: login.php");
    exit();
  } else {
    $message = 'Registration failed: ' . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Jutta Sansaar</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <div class="register-container">
    <h2>Create Account</h2>
    <?php if ($message): ?>
      <p class="error"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
      </div>
      <button type="submit" class="btn">Register</button>
    </form>
    <div class="login-link">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>
</body>
</html>