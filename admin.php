<?php
include 'db.php';

include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$totalMessages = 0; // Placeholder

$recentOrders = mysqli_query($conn, "SELECT o.id, u.name AS customer, o.created_at AS date, p.price * o.quantity AS total, o.status FROM orders o LEFT JOIN users u ON o.user_id = u.id LEFT JOIN products p ON o.product_id = p.id ORDER BY o.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shoe Store Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      padding: 1rem;
      color: #fff;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 0.5rem 0;
    }
    .sidebar a:hover {
      background-color: #495057;
      border-radius: 5px;
    }
    .dashboard-content {
      padding: 2rem;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">

      <nav class="col-md-2 d-none d-md-block sidebar">
        <h4>Admin Panel</h4>
        <a href="admin.php">Dashboard</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="orders.php">Orders</a>
        <a href="users.php">Users</a>
        <a href="logout.php">Logout</a>
      </nav>

      <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 dashboard-content">
        <h2>Dashboard Overview</h2>
        <div class="row mt-4">
          <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
              <div class="card-body">
                <h5 class="card-title">Total Products</h5>
                <p class="card-text"><?php echo $totalProducts; ?></p>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
              <div class="card-body">
                <h5 class="card-title">Orders</h5>
                <p class="card-text"><?php echo $totalOrders; ?></p>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
              <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="card-text"><?php echo $totalUsers; ?></p>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
              <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <p class="card-text"><?php echo $totalMessages; ?></p>
              </div>
            </div>
          </div>
        </div>

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
                <th scope="row">#<?php echo $row['id']; ?></th>
                <td><?php echo htmlspecialchars($row['customer']); ?></td>
                <td><?php echo $row['date']; ?></td>
                <td>$<?php echo number_format($row['total'], 2); ?></td>
                <td>
                  <span class="badge bg-<?php
                    echo $row['status'] === 'Accepted' ? 'success' :
                         ($row['status'] === 'Pending' ? 'warning' : 'danger');
                  ?>"><?php echo $row['status']; ?></span>
                </td>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
