<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Developer') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Users</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="content">
    <h2>Users</h2>

    <table class="table-standard">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $res = $conn->query("SELECT user_id, username, role FROM users ORDER BY user_id ASC");
        if ($res && $res->num_rows > 0):
            while ($u = $res->fetch_assoc()):
        ?>
        <tr>
          <td><?= (int)$u['user_id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td>
            <form class="inline-form" action="update_user_role.php" method="POST">
              <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
              <select name="role" class="inline-select">
                <option value="Admin" <?= $u['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Inspector" <?= $u['role'] === 'Inspector' ? 'selected' : '' ?>>Inspector</option>
                <option value="Developer" <?= $u['role'] === 'Developer' ? 'selected' : '' ?>>Developer</option>
                <option value="Viewer" <?= $u['role'] === 'Viewer' ? 'selected' : '' ?>>Viewer</option>
              </select>
              <button class="inline-submit" type="submit">Update</button>
            </form>
          </td>
          <td>
            <?php if ($u['user_id'] != $_SESSION['user_id']): // prevent deleting yourself ?>
              <a class="action-btn action-delete"
                 href="#"
                 onclick="confirmDelete('delete_user.php?id=<?= (int)$u['user_id'] ?>', 'Delete user &ldquo;<?= htmlspecialchars($u['username']) ?>&rdquo;? This action cannot be undone.')">
                 Delete
              </a>
            <?php else: ?>
              <em>Current User</em>
            <?php endif; ?>
          </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
          <td colspan="4">No users found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>

  <!-- Reusable confirmation modal -->
  <?php include 'modal.php'; ?>

  <script src="script.js"></script>
</body>
</html>
