<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role=$_SESSION['role'];
if(!in_array($role,['Admin','Developer'])) die("Access denied");

$id=intval($_POST['product_id']);
$name=$_POST['name'];
$category=$_POST['category'];
$supplier_id=intval($_POST['supplier_id']);

$stmt=$conn->prepare("UPDATE products SET name=?,category=?,supplier_id=? WHERE product_id=?");
$stmt->bind_param("ssii",$name,$category,$supplier_id,$id);
if($stmt->execute())
  echo "<script>alert('Product updated');window.location.href='products.php';</script>";
else
  echo "<script>alert('Error updating');window.location.href='products.php';</script>";
$stmt->close();
?>
