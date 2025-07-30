<?php
include 'db.php';
include 'session.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
  header("Location: login.php");
  exit();
}

$sql = "SELECT id, name, email, is_admin FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Users - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h1>Users</h1>
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
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
            <td>
            <?php if ($row['id'] != $_SESSION['user_id']): ?>
                <form method="post" action="toggle_admin.php" style="display:inline;">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="is_admin" value="<?php echo $row['is_admin'] ? 0 : 1; ?>">
                <button type="submit" class="btn btn-sm btn-<?php echo $row['is_admin'] ? 'warning' : 'success'; ?>">
                    <?php echo $row['is_admin'] ? 'Revoke Admin' : 'Make Admin'; ?>
                </button>
                </form>
            <?php else: ?>
                <span class="text-muted">You</span>
            <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5" class="text-center">No users found.</td></tr>
    <?php endif; ?>
    </tbody>


  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
