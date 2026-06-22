<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role,['Admin','Developer'])) { echo "<script>alert('Access denied'); window.location.href='batches.php';</script>"; exit(); }
if (!isset($_GET['id'])) { header("Location: batches.php"); exit(); }
$batch_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT b.*, p.name AS product_name FROM batches b LEFT JOIN products p ON b.product_id=p.product_id WHERE b.batch_id=?");
$stmt->bind_param("i",$batch_id); $stmt->execute(); $res = $stmt->get_result();
if ($res->num_rows===0) { echo "<script>alert('Not found');window.location.href='batches.php';</script>"; exit(); }
$batch = $res->fetch_assoc();
$products = $conn->query("SELECT product_id,name FROM products");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Edit Batch</title><link rel="stylesheet" href="style.css"></head><body>
<?php include 'header.php'; ?>
<section class="content">
  <h2>Edit Batch #<?= $batch_id ?></h2>
  <form action="update_batch.php" method="POST" class="form-container">
    <input type="hidden" name="batch_id" value="<?= $batch_id ?>">
    <label>Product</label>
    <select name="product_id" required>
      <?php while($p=$products->fetch_assoc()): ?>
        <option value="<?= $p['product_id'] ?>" <?= $p['product_id']==$batch['product_id']?'selected':'' ?>><?= htmlspecialchars($p['name']) ?></option>
      <?php endwhile; ?>
    </select>
    <label>Manufacture Date</label><input type="date" name="manufacture_date" value="<?= $batch['manufacture_date'] ?>" required>
    <label>Expiry Date</label><input type="date" name="expiry_date" value="<?= $batch['expiry_date'] ?>" required>
    <label>Quantity</label><input type="number" name="quantity" value="<?= $batch['quantity'] ?>" required>
    <button class="btn" type="submit">Update Batch</button>
  </form>
</section></body></html>
