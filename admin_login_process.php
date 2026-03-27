<?php
include 'db.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// check admin table
$sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $_SESSION['admin'] = $username;

    header("Location: admin_dashboard.php");
    exit();

} else {
    header("Location: admin_login.php?error=1");
    exit();
}
?>