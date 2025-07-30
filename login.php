<!-- login.php -->
<?php
include 'db.php';
include 'session.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);
  $user = mysqli_fetch_assoc($result);

  if ($user && password_verify($password, $user['password'])) {
    // Role check (assuming users table has is_admin tinyint(1))
    $is_admin_in_db = (int)$user['is_admin'] === 1;

    if (($role === 'admin' && $is_admin_in_db) || ($role === 'customer' && !$is_admin_in_db)) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['is_admin'] = $is_admin_in_db;
      $_SESSION['LAST_ACTIVITY'] = time();

      // Respect original page the user wanted to visit
      $redirect = $_SESSION['redirect_after_login'] ?? ($is_admin_in_db ? 'admin.php' : 'index.php');
      unset($_SESSION['redirect_after_login']);

      header("Location: {$redirect}");
      exit;
    } else {
      $message = 'Role mismatch. Please select the correct login role.';
    }
  } else {
    $message = 'Invalid email or password';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Jutta Sansaar</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <header>
    <div class="container">
      <h1>👟 Jutta Sansaar</h1>
      <nav id="nav-menu" aria-label="Primary">
      <a href="index.php" class="nav-link ">Home</a>
      <a href="products.php" class="nav-link" aria-current="page">Shop</a>
      <a href="cart.php" class="nav-link">Cart</a>
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

  <main class="page-content">
  <div class="login-container">
    <h2>Login to Jutta Sansaar</h2>
    <?php if ($message): ?>
      <p class="error"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
      </div>
      <div class="form-group">
        <label for="role">Login as</label>
        <select name="role" id="role" required>
          <option value="customer">Customer</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit" class="btn">Login</button>
    </form>
    <div class="signup-link">
      <p>Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
  </div>
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

    </script>
    <?php if (isset($_GET['msg'])): ?>
  <div id="popup-modal" style="
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;">
    <div style="
      background: #fff;
      padding: 1.5rem 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      max-width: 90%;
      width: 400px;
      text-align: center;
      font-family: 'Segoe UI', sans-serif;
    ">
      <h3 style="margin-bottom: 1rem;">🔒 Login Required</h3>
      <p style="margin-bottom: 1.5rem;"><?= htmlspecialchars($_GET['msg']) ?></p>
      <button onclick="document.getElementById('popup-modal').style.display='none'" style="
        background: #222;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 5px;
        cursor: pointer;
      ">OK</button>
    </div>
  </div>
<?php endif; ?>
</body>
</html>
