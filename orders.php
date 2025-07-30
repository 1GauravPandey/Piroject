<?php
include 'db.php';
include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

// Fetch all orders with user info
$sql = "SELECT 
          o.id, o.user_id, o.name, o.phone, o.address, o.payment_method, o.status, o.total, o.created_at,
          u.email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Orders - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h1>Orders</h1>
  <a href="admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Payment Method</th>
        <th>Total ($)</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td>#<?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['phone']); ?></td>
          <td><?php echo nl2br(htmlspecialchars($row['address'])); ?></td>
          <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
          <td><?php echo number_format($row['total'], 2); ?></td>
          <td>
            <span class="badge bg-<?php
              echo $row['status'] === 'Accepted' ? 'success' :
                   ($row['status'] === 'Pending' ? 'warning' : 'danger');
            ?>"><?php echo $row['status']; ?></span>
          </td>
          <td><?php echo $row['created_at']; ?></td>
          <td>
            <?php if ($row['status'] === 'Pending'): ?>
              <a href="update_order_status.php?id=<?php echo $row['id']; ?>&status=Accepted" class="btn btn-sm btn-success">Accept</a>
              <a href="update_order_status.php?id=<?php echo $row['id']; ?>&status=Rejected" class="btn btn-sm btn-danger">Reject</a>
            <?php else: ?>
              <span class="text-muted">No Actions</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="10" class="text-center">No orders found.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
