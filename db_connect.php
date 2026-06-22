<?php
$servername = "localhost"; // or your DB host
$username = "root";        // your MySQL username
$password = "Kshitij@1506";            // your MySQL password (keep blank if none)
$dbname = "food_safety_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>