<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Username exists'); window.location.href='register.html';</script>";
        exit();
    }

    $ins = $conn->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
    $ins->bind_param("sss", $username, $password, $role);
    if ($ins->execute()) {
        echo "<script>alert('Registered successfully'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error registering'); window.location.href='register.html';</script>";
    }
    $ins->close();
} else {
    header("Location: register.html");
}
?>
