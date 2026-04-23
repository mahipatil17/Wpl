<?php
include 'db.php';
session_start();

$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';
$remember = isset($_POST['remember']);

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        /* ── COOKIE LOGIC ── */
        if ($remember) {
            /* Store email for 7 days — HttpOnly so JS cannot read it */
            setcookie("user_email", $email, time() + (7 * 24 * 60 * 60), "/", "", false, true);
        } else {
            /* User unchecked box — delete any existing cookie */
            setcookie("user_email", "", time() - 3600, "/");
        }

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
