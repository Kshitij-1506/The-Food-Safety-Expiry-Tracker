<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Products</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<section class="content">
  <h2>Products</h2>

  <?php if (in_array($role, ['Admin','Developer'])): ?>
  <div class="form-container">
    <h3>Add Product</h3>
    <form action="insert_product.php" method="POST">
      <label>Name</label>
      <input name="name" required>
      <label>Category</label>
      <input name="category">
      <label>Supplier</label>
      <select name="supplier_id" required>
        <option value="">--Select--</option>
        <?php
          $sup = $conn->query("SELECT supplier_id, name FROM suppliers");
          while ($s = $sup->fetch_assoc()) {
            echo "<option value='{$s['supplier_id']}'>" . htmlspecialchars($s['name']) . "</option>";
          }
        ?>
      </select>
      <button class="btn" type="submit">Add Product</button>
    </form>
  </div>
  <?php endif; ?>

  <h3>All Products</h3>
  <table class="table-standard">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Category</th><th>Supplier</th><th>Total Batches</th>
        <?php if (in_array($role, ['Admin','Developer'])) echo "<th>Actions</th>"; ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT p.*, s.name AS supplier_name, COUNT(b.batch_id) AS total_batches
              FROM products p
              LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
              LEFT JOIN batches b ON b.product_id = p.product_id
              GROUP BY p.product_id";
      $res = $conn->query($sql);
      while ($r = $res->fetch_assoc()):
      ?>
      <tr>
        <td><?= $r['product_id'] ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['category']) ?></td>
        <td><?= htmlspecialchars($r['supplier_name']) ?></td>
        <td><?= $r['total_batches'] ?></td>
        <?php if (in_array($role, ['Admin','Developer'])): ?>
        <td>
          <a class="action-btn" href="edit_product.php?id=<?= $r['product_id'] ?>">Edit</a>
          <a class="action-btn action-delete" href="#" onclick="confirmDelete('delete_product.php?id=<?= $r['product_id'] ?>', 'Delete product “<?= htmlspecialchars($r['name']) ?>”? This will remove all its batches.')">Delete</a>
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
