<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role,['Admin','Developer'])) die("Access denied");
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $batch_id = intval($_POST['batch_id']);
    $product_id = intval($_POST['product_id']);
    $manufacture_date = $_POST['manufacture_date'];
    $expiry_date = $_POST['expiry_date'];
    $quantity = intval($_POST['quantity']);
    $stmt = $conn->prepare("UPDATE batches SET product_id=?,manufacture_date=?,expiry_date=?,quantity=? WHERE batch_id=?");
    $stmt->bind_param("issii",$product_id,$manufacture_date,$expiry_date,$quantity,$batch_id);
    if ($stmt->execute()) echo "<script>alert('Updated');window.location.href='batches.php';</script>";
    else echo "<script>alert('Error');window.location.href='batches.php';</script>";
    $stmt->close();
} else header("Location: batches.php");
?>
