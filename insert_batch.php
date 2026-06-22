<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role,['Admin','Developer'])) die("Access denied");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $manufacture_date = $_POST['manufacture_date'];
    $expiry_date = $_POST['expiry_date'];
    $quantity = intval($_POST['quantity']);
    $stmt = $conn->prepare("CALL AddBatch(?,?,?,?)");
    $stmt->bind_param("isss",$product_id,$manufacture_date,$expiry_date,$quantity);
    if ($stmt->execute()) echo "<script>alert('Batch added');window.location.href='batches.php';</script>";
    else echo "<script>alert('Error: ".addslashes($conn->error)."');window.location.href='batches.php';</script>";
    $stmt->close();
} else header("Location: batches.php");
?>
