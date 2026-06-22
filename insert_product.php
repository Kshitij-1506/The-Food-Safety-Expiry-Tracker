<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role,['Admin','Developer'])) die("Access denied");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; $category = $_POST['category']; $supplier_id = $_POST['supplier_id']?:null;
    $stmt = $conn->prepare("INSERT INTO products (name,category,supplier_id) VALUES (?,?,?)");
    $stmt->bind_param("ssi",$name,$category,$supplier_id);
    if ($stmt->execute()) echo "<script>alert('Product added'); window.location.href='products.php';</script>";
    else echo "<script>alert('Error'); window.location.href='products.php';</script>";
    $stmt->close();
} else header("Location: products.php");
?>
