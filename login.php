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
    if (($role === 'admin' && $user['is_admin']) || ($role === 'customer' && !$user['is_admin'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['is_admin'] = $user['is_admin'];

      // Redirect based on role
      if ($user['is_admin']) {
        header("Location: admin.php");
      } else {
        header("Location: index.php");
      }
      exit();
    } else {
      $message = 'Role mismatch. Please select the correct login role.';
    }
  }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Jutta Sansaar</title>
  <!-- <link rel="stylesheet" href="styles.css"> -->
  <style>
        * {
      box-sizing: border-box;
      margin: 0; padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
main.page-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
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
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background: url('https://cloudfront-us-east-2.images.arcpublishing.com/reuters/NKYZ3DCPBRMHVL3LP65XEHONXA.jpg') no-repeat center center/cover ;
      color: #222;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .login-container {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 1rem;
      color: #333;
    }
    .form-group {
      margin-bottom: 1rem;
    }
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #555;
    }
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }
    .btn {
      width: 100%;
      background-color: #ff7e5f;
      border: none;
      padding: 0.75rem;
      font-size: 1rem;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .btn:hover {
      background-color: #eb6a48;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
    }
    .signup-link {
      text-align: center;
      margin-top: 1rem;
    }
    .signup-link a {
      color: #ff7e5f;
      text-decoration: none;
      font-weight: bold;
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
      <a href="cart.php" class="nav-link">Cart</a>
      <a href="checkout.php" class="nav-link">Checkout</a>
      <a href="login.php" class="nav-link active">Login</a>
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
</body>
</html>
