<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Suppliers</title><link rel="stylesheet" href="style.css"></head><body>
<?php include 'header.php'; ?>
<section class="content">
  <h2>Suppliers</h2>

  <?php if (in_array($role, ['Admin','Developer'])): ?>
  <div class="form-container">
    <h3>Add Supplier</h3>
    <form action="insert_supplier.php" method="POST">
      <label>Name</label><input name="name" required>
      <label>Contact</label><input name="contact">
      <label>Location</label><input name="location">
      <button class="btn" type="submit">Add Supplier</button>
    </form>
  </div>
  <?php endif; ?>

  <table class="table-standard">
    <thead><tr><th>ID</th><th>Name</th><th>Contact</th><th>Location</th><th>Total Products</th>
      <?php if (in_array($role,['Admin','Developer'])) echo '<th>Actions</th>'; ?>
    </tr></thead>
    <tbody>
      <?php
        $res = $conn->query("SELECT s.*, COUNT(p.product_id) AS total_products FROM suppliers s LEFT JOIN products p ON p.supplier_id = s.supplier_id GROUP BY s.supplier_id");
        while ($r=$res->fetch_assoc()):
      ?>
      <tr>
        <td><?= $r['supplier_id'] ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['contact']) ?></td>
        <td><?= htmlspecialchars($r['location']) ?></td>
        <td><?= $r['total_products'] ?></td>
        <?php if (in_array($role,['Admin','Developer'])): ?>
        <td>
          <a class="action-btn action-delete" href="#" onclick="confirmDelete('delete_supplier.php?id=<?= $r['supplier_id'] ?>', 'Delete supplier “<?= htmlspecialchars($r['name']) ?>”? This will remove all linked products and batches.')">Delete</a>
        </td>
        <?php endif; ?>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>
<?php include 'modal.php'; ?>
<script src="script.js"></script>
</body>
</html>