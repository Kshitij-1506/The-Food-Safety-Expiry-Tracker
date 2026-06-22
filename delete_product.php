<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role=$_SESSION['role'];
if(!in_array($role,['Admin','Developer'])) die("Access denied");

$id=intval($_GET['id']);
$stmt=$conn->prepare("DELETE FROM products WHERE product_id=?");
$stmt->bind_param("i",$id);
if($stmt->execute())
  echo "<script>alert('Product deleted');window.location.href='products.php';</script>";
else
  echo "<script>alert('Error deleting');window.location.href='products.php';</script>";
$stmt->close();
?>
