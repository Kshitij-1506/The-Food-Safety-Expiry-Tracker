<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role, ['Inspector','Developer'])) { echo "<script>alert('Access denied'); location.href='index.php';</script>"; exit(); }

if (!isset($_GET['id'])) { header("Location: inspections.php"); exit(); }
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM inspections WHERE inspection_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) { echo "<script>alert('Inspection not found'); location.href='inspections.php';</script>"; exit(); }
$insp = $res->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Edit Inspection</title><link rel="stylesheet" href="style.css"></head><body>
<?php include 'header.php'; ?>
<section class="content">
  <h2>Edit Inspection #<?= $id ?></h2>
  <form action="update_inspection.php" method="POST" class="form-container">
    <input type="hidden" name="inspection_id" value="<?= $id ?>">
    <label>Batch ID</label>
    <input type="number" name="batch_id" value="<?= htmlspecialchars($insp['batch_id']) ?>" required>
    <label>Inspector Name</label>
    <input type="text" name="inspector_name" value="<?= htmlspecialchars($insp['inspector_name']) ?>" required>
    <label>Inspection Date</label>
    <input type="date" name="inspection_date" value="<?= $insp['inspection_date'] ?>" required>
    <label>Status</label>
    <select name="status" required>
      <option value="Pass" <?= $insp['status']=='Pass' ? 'selected' : '' ?>>Pass</option>
      <option value="Fail" <?= $insp['status']=='Fail' ? 'selected' : '' ?>>Fail</option>
    </select>
    <label>Remarks</label>
    <textarea name="remarks" rows="3"><?= htmlspecialchars($insp['remarks']) ?></textarea>
    <button class="btn" type="submit">Update Inspection</button>
  </form>
</section>
</body></html>
