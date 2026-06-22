<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role, ['Admin','Developer'])) { die("Access denied"); }

if (!isset($_GET['id'])) header("Location: suppliers.php");
$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) echo "<script>alert('Supplier deleted'); window.location.href='suppliers.php';</script>";
else echo "<script>alert('Error deleting supplier'); window.location.href='suppliers.php';</script>";
$stmt->close();
?>
