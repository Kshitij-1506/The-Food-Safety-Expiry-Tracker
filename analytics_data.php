<?php
include 'db_connect.php';
$data = [];

$res = $conn->query("SELECT COUNT(*) AS total FROM products");
$data['totalProducts'] = ($res ? (int)$res->fetch_assoc()['total'] : 0);

$res = $conn->query("SELECT COUNT(*) AS nearExpiry FROM batches WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
$data['nearExpiry'] = ($res ? (int)$res->fetch_assoc()['nearExpiry'] : 0);

$res = $conn->query("SELECT COUNT(*) AS failed FROM inspections WHERE status='Fail'");
$data['failedInspections'] = ($res ? (int)$res->fetch_assoc()['failed'] : 0);

$res = $conn->query("SELECT status, COUNT(*) AS cnt FROM inspections GROUP BY status");
$chart = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $chart[$r['status']] = (int)$r['cnt'];
}
$data['chart'] = $chart;

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();
?>
