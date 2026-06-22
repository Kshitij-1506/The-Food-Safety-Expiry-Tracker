<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username'])) header("Location: login.html");
$role = $_SESSION['role'];
if (!in_array($role, ['Inspector','Developer'])) { die("Access denied"); }

if (!isset($_GET['id'])) header("Location: inspections.php");
$id = intval($_GET['id']);

$res = $conn->query("SELECT batch_id FROM inspections WHERE inspection_id = $id");
$batch_id = ($res && $res->num_rows) ? (int)$res->fetch_assoc()['batch_id'] : null;

$stmt = $conn->prepare("DELETE FROM inspections WHERE inspection_id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    if ($batch_id !== null) {
        $conn->query("UPDATE batches SET last_inspection_status = (SELECT status FROM inspections WHERE inspections.batch_id = batches.batch_id ORDER BY inspection_date DESC, inspection_id DESC LIMIT 1) WHERE batch_id = $batch_id");
    }
    echo "<script>alert('Inspection deleted'); window.location.href='inspections.php';</script>";
} else {
    echo "<script>alert('Error deleting'); window.location.href='inspections.php';</script>";
}
$stmt->close();
?>
