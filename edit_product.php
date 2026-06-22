<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role=$_SESSION['role'];
if (!in_array($role,['Admin','Developer'])) die("Access denied");

$id=intval($_GET['id']);
$res=$conn->query("SELECT * FROM products WHERE product_id=$id");
if(!$res || $res->num_rows==0){ echo "<script>alert('Product not found');location.href='products.php';</script>"; exit(); }
$p=$res->fetch_assoc();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Edit Product</title><link rel="stylesheet" href="style.css"></head><body>
<?php include 'header.php'; ?>
<section class="content">
<h2>Edit Product #<?= $p['product_id'] ?></h2>
<form action="update_product.php" method="POST" class="form-container">
  <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
  <label>Name</label><input name="name" value="<?= htmlspecialchars($p['name']) ?>" required>
  <label>Category</label><input name="category" value="<?= htmlspecialchars($p['category']) ?>">
  <label>Supplier</label>
  <select name="supplier_id" required>
    <?php
      $sup=$conn->query("SELECT supplier_id,name FROM suppliers");
      while($s=$sup->fetch_assoc()){
        $sel=$s['supplier_id']==$p['supplier_id']?'selected':'';
        echo "<option value='{$s['supplier_id']}' $sel>".htmlspecialchars($s['name'])."</option>";
      }
    ?>
  </select>
  <button class="btn" type="submit">Update Product</button>
</form>
</section>
</body></html>
