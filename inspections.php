<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];

if (!in_array($role, ['Inspector','Developer'])) {
    echo "<script>alert('Access denied: Inspections are available to Inspectors and Developers only.'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Inspections - Food Safety Tracker</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <section class="content">
    <h2>Inspections</h2>

    <div class="form-container">
      <h3>Record Inspection</h3>
      <form action="insert_inspection.php" method="POST">
        <label>Batch ID</label>
        <input type="number" name="batch_id" required>
        <label>Inspector Name</label>
        <input type="text" name="inspector_name" required value="<?= htmlspecialchars($_SESSION['username']) ?>">
        <label>Inspection Date</label>
        <input type="date" name="inspection_date" required>
        <label>Status</label>
        <select name="status" required>
          <option value="Pass">Pass</option>
          <option value="Fail">Fail</option>
        </select>
        <label>Remarks</label>
        <textarea name="remarks" rows="3"></textarea>
        <button class="btn" type="submit">Save Inspection</button>

      </form>     
    </div>

    <h3>All Inspections</h3>
    <table class="table-standard">
      <thead>
        <tr>
          <th>ID</th><th>Batch</th><th>Product</th><th>Inspector</th><th>Date</th><th>Status</th><th>Remarks</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT i.*, b.batch_id, p.name AS product_name 
                FROM inspections i 
                LEFT JOIN batches b ON i.batch_id = b.batch_id
                LEFT JOIN products p ON b.product_id = p.product_id
                ORDER BY i.inspection_date DESC";
        $res = $conn->query($sql);
        while ($r = $res->fetch_assoc()):
        ?>
        <tr>
          <td><?= $r['inspection_id'] ?></td>
          <td><?= $r['batch_id'] ?></td>
          <td><?= htmlspecialchars($r['product_name'] ?: 'Unknown') ?></td>
          <td><?= htmlspecialchars($r['inspector_name']) ?></td>
          <td><?= $r['inspection_date'] ?></td>
          <td><?= $r['status'] ?></td>
          <td><?= htmlspecialchars($r['remarks']) ?></td>
          <td>
            <a class="action-btn" href="edit_inspection.php?id=<?= $r['inspection_id'] ?>">Edit</a>
            <a class="action-btn action-delete" href="#" onclick="confirmDelete('delete_inspection.php?id=<?= $r['inspection_id'] ?>', 'Delete inspection #<?= $r['inspection_id'] ?> for batch #<?= $r['batch_id'] ?>?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>
  <?php include 'modal.php'; ?>
<script src="script.js"></script>
</body>
</html>
