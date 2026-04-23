<?php
include 'db.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE username=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['admin'] = $username;
    header("Location: admin_dashboard.php");
    exit();
} else {
    header("Location: admin_login.php?error=1");
    exit();
}
?>
