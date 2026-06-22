<?php
// delete_user.php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Developer') {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = intval($_GET['id']);

// Prevent Developer from deleting themselves
if ($id == $_SESSION['user_id']) {
    echo "<script>alert('You cannot delete your own account.'); window.location.href='users.php';</script>";
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully.'); window.location.href='users.php';</script>";
} else {
    echo "<script>alert('Error deleting user.'); window.location.href='users.php';</script>";
}

$stmt->close();
?>
