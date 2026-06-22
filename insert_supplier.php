<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role, ['Admin','Developer'])) { die("Access denied"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $contact = $conn->real_escape_string(trim($_POST['contact']));
    $location = $conn->real_escape_string(trim($_POST['location']));
    $stmt = $conn->prepare("INSERT INTO suppliers (name, contact, location) VALUES (?,?,?)");
    $stmt->bind_param("sss", $name, $contact, $location);
    if ($stmt->execute()) echo "<script>alert('Supplier added'); window.location.href='suppliers.php';</script>";
    else echo "<script>alert('Error adding supplier'); window.location.href='suppliers.php';</script>";
    $stmt->close();
} else header("Location: suppliers.php");
?>
