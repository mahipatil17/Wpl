<?php
include 'db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    // password verify (since you are using hash)
    if (password_verify($password, $user['password'])) {

        // store full user
        $_SESSION['user'] = $user;

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