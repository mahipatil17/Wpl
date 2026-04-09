<?php
include 'db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];

        if ($user['interests_selected'] == 0) {
            header("Location: interests.php");
        } else {
            header("Location: dashboard.php");
        }
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
