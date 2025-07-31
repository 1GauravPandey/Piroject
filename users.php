<?php
// Include database connection and session management
include 'db.php';
include 'session.php';

// Only allow access if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

// Fetch all users with their ID, name, email, and admin status, ordered by newest first
$sql = "SELECT id, name, email, is_admin FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Users - Admin</title>
  <!-- Bootstrap CSS for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h1>Users</h1>
  <!-- Back button to admin dashboard -->
  <a href="admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Admin</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <!-- Display user ID -->
        <td>#<?php echo $row['id']; ?></td>
        <!-- Safely output user name and email to prevent XSS -->
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <!-- Show if user is admin -->
        <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
        <td>
          <?php if ($row['id'] != $_SESSION['user_id']): ?>
            <!-- Form to toggle admin status -->
            <form method="post" action="toggle_admin.php" style="display:inline;">
              <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
              <!-- Toggle admin: if currently admin, set to 0; else 1 -->
              <input type="hidden" name="is_admin" value="<?php echo $row['is_admin'] ? 0 : 1; ?>">
              <!-- Button text and color changes based on admin status -->
              <button type="submit" class="btn btn-sm btn-<?php echo $row['is_admin'] ? 'warning' : 'success'; ?>">
                <?php echo $row['is_admin'] ? 'Revoke Admin' : 'Make Admin'; ?>
              </button>
            </form>
          <?php else: ?>
            <!-- Disable action for the currently logged-in user -->
            <span class="text-muted">You</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <!-- Show message if no users found -->
      <tr><td colspan="5" class="text-center">No users found.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
