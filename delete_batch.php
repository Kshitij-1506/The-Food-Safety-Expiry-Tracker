<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role,['Admin','Developer'])) die("Access denied");
if (isset($_GET['id'])) {
    $batch_id = intval($_GET['id']);
    $conn->query("DELETE FROM inspections WHERE batch_id = $batch_id");
    $stmt = $conn->prepare("DELETE FROM batches WHERE batch_id=?");
    $stmt->bind_param("i",$batch_id);
    if ($stmt->execute()) echo "<script>alert('Deleted');window.location.href='batches.php';</script>";
    else echo "<script>alert('Error deleting');window.location.href='batches.php';</script>";
    $stmt->close();
} else header("Location: batches.php");
?>
