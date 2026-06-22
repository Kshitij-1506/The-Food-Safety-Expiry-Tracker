<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role, ['Inspector','Developer'])) { die("Access denied"); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: inspections.php");

$id = intval($_POST['inspection_id']);
$batch_id = intval($_POST['batch_id']);
$inspector_name = $_POST['inspector_name'];
$inspection_date = $_POST['inspection_date'];
$status = $_POST['status'];
$remarks = $_POST['remarks'];

$stmt = $conn->prepare("UPDATE inspections SET batch_id=?, inspector_name=?, inspection_date=?, status=?, remarks=? WHERE inspection_id=?");
$stmt->bind_param("issssi", $batch_id, $inspector_name, $inspection_date, $status, $remarks, $id);
if ($stmt->execute()) {
    $conn->query("UPDATE batches SET last_inspection_status = (SELECT status FROM inspections WHERE inspections.batch_id = batches.batch_id ORDER BY inspection_date DESC, inspection_id DESC LIMIT 1) WHERE batch_id = $batch_id");
    echo "<script>alert('Inspection updated'); window.location.href='inspections.php';</script>";
} else {
    echo "<script>alert('Error updating'); window.location.href='inspections.php';</script>";
}
$stmt->close();
?>
