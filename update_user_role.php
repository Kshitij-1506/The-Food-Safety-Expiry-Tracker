<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Developer') { die("Access denied"); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: users.php");
$user_id = intval($_POST['user_id']);
$role = $_POST['role'];

$allowed = ['Admin','Inspector','Developer','Viewer'];
if (!in_array($role, $allowed)) { echo "<script>alert('Invalid role'); window.location.href='users.php';</script>"; exit(); }

$stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
$stmt->bind_param("si", $role, $user_id);
if ($stmt->execute()) echo "<script>alert('Role updated'); window.location.href='users.php';</script>";
else echo "<script>alert('Error updating role'); window.location.href='users.php';</script>";
$stmt->close();
?>
