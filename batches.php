<?php
// batches.php (updated to show inspection remarks)
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Batches</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <section class="content">
    <h2>Batches</h2>

    <?php if (in_array($role, ['Admin', 'Developer'])): ?>
    <div class="form-container">
      <h3>Add Batch</h3>
      <form action="insert_batch.php" method="POST">
        <label>Product</label>
        <select name="product_id" required>
          <option value="">--Select Product--</option>
          <?php
            $p = $conn->query("SELECT product_id, name FROM products");
            while ($r = $p->fetch_assoc()) {
              echo "<option value='{$r['product_id']}'>" . htmlspecialchars($r['name']) . "</option>";
            }
          ?>
        </select>
        <label>Manufacture Date</label>
        <input type="date" name="manufacture_date" required>
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" required>
        <label>Quantity</label>
        <input type="number" name="quantity" min="1" required>
        <button class="btn" type="submit">Add Batch</button>
      </form>
    </div>
    <?php endif; ?>

    <h3>All Batches</h3>
    <table class="table-standard">
      <thead>
        <tr>
          <th>ID</th>
          <th>Product</th>
          <th>Manufacture Date</th>
          <th>Expiry Date</th>
          <th>Quantity</th>
          <th>Inspection Status</th>
          <th>Inspection Remarks</th>
          <?php if (in_array($role, ['Admin','Developer'])) echo '<th>Actions</th>'; ?>
        </tr>
      </thead>
      <tbody>
        <?php
        // ✅ Fetch latest inspection for each batch using subquery
        $sql = "
          SELECT 
            b.batch_id, 
            p.name AS product_name, 
            b.manufacture_date, 
            b.expiry_date, 
            b.quantity,
            i.status AS inspection_status,
            i.remarks AS inspection_remarks
          FROM batches b
          LEFT JOIN products p ON b.product_id = p.product_id
          LEFT JOIN (
              SELECT insp1.batch_id, insp1.status, insp1.remarks
              FROM inspections insp1
              INNER JOIN (
                SELECT batch_id, MAX(inspection_date) AS last_date
                FROM inspections
                GROUP BY batch_id
              ) insp2
              ON insp1.batch_id = insp2.batch_id AND insp1.inspection_date = insp2.last_date
          ) i ON b.batch_id = i.batch_id
          ORDER BY b.batch_id ASC;
        ";

        $res = $conn->query($sql);
        while ($row = $res->fetch_assoc()):
          $status = $row['inspection_status'] ?: 'No Inspection Yet';
          $remarks = $row['inspection_remarks'] ?: '-';
        ?>
        <tr>
          <td><?= $row['batch_id'] ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= htmlspecialchars($row['manufacture_date']) ?></td>
          <td><?= htmlspecialchars($row['expiry_date']) ?></td>
          <td><?= htmlspecialchars($row['quantity']) ?></td>
          <td><?= htmlspecialchars($status) ?></td>
          <td><?= htmlspecialchars($remarks) ?></td>
          <?php if (in_array($role, ['Admin','Developer'])): ?>
          <td>
            <a class="action-btn" href="edit_batch.php?id=<?= $row['batch_id'] ?>">Edit</a>
            <a class="action-btn action-delete" href="#" onclick="confirmDelete('delete_batch.php?id=<?= $row['batch_id'] ?>', 'Delete batch #<?= $row['batch_id'] ?>? This will remove all related inspections.')">Delete</a>
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
