<?php
include 'db_connect.php';

$sql = "
SELECT b.batch_id, b.product_id, COALESCE(p.name,'Unknown') AS product_name,
       b.manufacture_date, b.expiry_date, b.quantity, b.last_inspection_status
FROM batches b
LEFT JOIN products p ON b.product_id = p.product_id
ORDER BY b.expiry_date ASC;
";

$res = $conn->query($sql);
$batches = [];
$today = date('Y-m-d');
$soon = date('Y-m-d', strtotime('+7 days'));
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $expiry = $row['expiry_date'];
        if ($expiry < $today) $expiry_status = "Expired";
        elseif ($expiry <= $soon) $expiry_status = "Expiring Soon";
        else $expiry_status = "Valid";

        $batches[] = [
            "batch_id" => (int)$row['batch_id'],
            "product_id" => (int)$row['product_id'],
            "product_name" => $row['product_name'],
            "manufacture_date" => $row['manufacture_date'],
            "expiry_date" => $row['expiry_date'],
            "quantity" => (int)$row['quantity'],
            "expiry_status" => $expiry_status,
            "last_inspection_status" => $row['last_inspection_status'] ? $row['last_inspection_status'] : 'N/A'
        ];
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($batches);
$conn->close();
?>
