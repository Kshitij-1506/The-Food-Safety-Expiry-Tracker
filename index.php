<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Food Safety Tracker</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="content">
    <h2>Dashboard Overview</h2>

    <div class="dashboard-overview">
      <div class="dashboard-cards">
        <div class="dashboard-card" id="totalProducts">Loading...</div>
        <div class="dashboard-card" id="nearExpiry">Loading...</div>
        <div class="dashboard-card" id="failedInspections">Loading...</div>
      </div>
      <div id="chartContainer">
        <canvas id="inspectionChart"></canvas>
      </div>
    </div>

    <h3>Batch Expiry Table</h3>
    <table class="table-standard">
      <thead>
        <tr>
          <th>Batch ID</th>
          <th>Product</th>
          <th>Expiry Date</th>
          <th>Expiry Status</th>
          <th>Inspection Status</th>
          <?php if (in_array($role, ['Admin','Developer'])): ?>
            <th>Actions</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody id="batchData">
        <!-- Filled dynamically by script.js -->
      </tbody>
    </table>
  </section>

  <!-- Reusable Confirmation Modal -->
  <?php include 'modal.php'; ?>

  <script>
    // Pass user role to JS for conditional button rendering
    window.userRole = "<?= $role ?>";
  </script>
  <script src="script.js"></script>
</body>
</html>
