<?php
// Include the database connection file
include 'db.php';

// Include session handling file
include 'session.php';

// Check if the user is logged in and is an admin
// If not, redirect to the login page
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

// Fetch total number of products from the database
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];

// Fetch total number of orders from the database
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];

// Fetch total number of registered users from the database
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];

// Placeholder for total messages, can be updated if message system is added
$totalMessages = 0;

// Fetch 5 most recent orders with user names, order date, total amount and status
$recentOrders = mysqli_query($conn, "
    SELECT 
        o.id, 
        u.name AS customer, 
        o.created_at AS date, 
        o.total, 
        o.status 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shoe Store Admin Dashboard</title>

  <!-- Bootstrap CSS for responsive layout and styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Custom Admin CSS -->
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar Navigation -->
      <nav class="col-md-2 d-none d-md-block sidebar">
        <h4>Admin Panel</h4>
        <a href="admin.php">Dashboard</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="orders.php">Orders</a>
        <a href="users.php">Users</a>
        <a href="logout.php">Logout</a>
      </nav>

      <!-- Main Dashboard Content Area -->
      <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 dashboard-content">
        <h2>Dashboard Overview</h2>

        <!-- Stats Cards Section -->
        <div class="row mt-4">
          <!-- Total Products Card -->
          <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
              <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <p class="card-text"><?php echo $totalProducts; ?></p>
              </div>
            </div>
          </div>

          <!-- Total Orders Card -->
          <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
              <div class="card-body">
                <h5 class="card-title">Orders</h5>
                <p class="card-text"><?php echo $totalOrders; ?></p>
              </div>
            </div>
          </div>

          <!-- Total Users Card -->
          <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
              <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="card-text"><?php echo $totalUsers; ?></p>
              </div>
            </div>
          </div>

          <!-- Messages Placeholder Card -->
          <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
              <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <p class="card-text"><?php echo $totalMessages; ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Orders Table -->
        <h3 class="mt-5">Recent Orders</h3>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Order ID</th> 
              <th scope="col">Customer</th>
              <th scope="col">Date</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = mysqli_fetch_assoc($recentOrders)): ?>
            <tr>
              <!-- Display Order ID -->
              <th scope="row">#<?php echo $row['id']; ?></th>

              <!-- Display Customer Name (escaped for security) -->
              <td><?php echo htmlspecialchars($row['customer']); ?></td>

              <!-- Display Order Date -->
              <td><?php echo $row['date']; ?></td>

              <!-- Display Order Total with formatting -->
              <td>रु<?php echo number_format($row['total'], 2); ?></td>

              <!-- Display Order Status with color-coded badge -->
              <td>
                <span class="badge bg-<?php
                  echo $row['status'] === 'Accepted' ? 'success' :
                      ($row['status'] === 'Pending' ? 'warning' : 'danger');
                ?>"><?php echo $row['status']; ?></span>
              </td>

              <!-- Accept/Reject Buttons if order is pending -->
              <td>
                <?php if ($row['status'] === 'Pending'): ?>
                  <a href="update_order_status.php?id=<?php echo $row['id']; ?>&status=Accepted" class="btn btn-sm btn-success">Accept</a>
                  <a href="update_order_status.php?id=<?php echo $row['id']; ?>&status=Rejected" class="btn btn-sm btn-danger">Reject</a>
                <?php else: ?>
                  <span class="text-muted">No Action</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
