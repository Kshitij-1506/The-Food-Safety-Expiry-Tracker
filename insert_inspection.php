<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role,['Inspector','Admin','Developer'])) { die("Access denied"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id = intval($_POST['batch_id']);
    $inspector_name = $_POST['inspector_name'];
    $inspection_date = $_POST['inspection_date'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("CALL AddInspection(?,?,?,?,?)");
    $stmt->bind_param("issss", $batch_id, $inspector_name, $inspection_date, $status, $remarks);
    if ($stmt->execute()) {
        echo "<script>alert('Inspection recorded'); window.location.href='inspections.php';</script>";
    } else {
        echo "<script>alert('Error: ".addslashes($conn->error)."'); window.location.href='inspections.php';</script>";
    }
    $stmt->close();
} else header("Location: inspections.php");
?>
