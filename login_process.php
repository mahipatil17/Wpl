<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Get user by email only
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {

        $_SESSION['user'] = $row['email'];
        $_SESSION['name'] = $row['name'];

        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: login.php?error=1");
        exit();
    }

} else {
    header("Location: login.php?error=1");
    exit();
}
?>