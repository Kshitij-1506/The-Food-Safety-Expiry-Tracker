<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';
?>
<header>
  <h1>Food Safety & Expiry Tracker</h1>
  <nav>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF'])=='index.php' ? 'active' : '' ?>">Dashboard</a>
    <a href="batches.php" class="<?= basename($_SERVER['PHP_SELF'])=='batches.php' ? 'active' : '' ?>">Batches</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF'])=='products.php' ? 'active' : '' ?>">Products</a>
    <a href="suppliers.php" class="<?= basename($_SERVER['PHP_SELF'])=='suppliers.php' ? 'active' : '' ?>">Suppliers</a>

    <?php if (in_array($role, ['Inspector','Developer'])): ?>
      <a href="inspections.php" class="<?= basename($_SERVER['PHP_SELF'])=='inspections.php' ? 'active' : '' ?>">Inspections</a>
    <?php endif; ?>

    <?php if ($role === 'Developer'): ?>
      <a href="users.php" class="<?= basename($_SERVER['PHP_SELF'])=='users.php' ? 'active' : '' ?>">Users</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['username'])): ?>
      <a href="logout.php">Logout (<?= htmlspecialchars($role) ?>)</a>
    <?php else: ?>
      <a href="login.html">Login</a>
      <a href="register.html">Register</a>
    <?php endif; ?>
  </nav>
</header>
