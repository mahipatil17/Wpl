<?php
include 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$branch = $_POST['branch'];
$year = $_POST['year'];

// 🔐 Encrypt password
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, branch, year, password)
VALUES ('$name', '$email', '$branch', '$year', '$password')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.php?success=1");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>