<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$name        = $conn->real_escape_string($_POST['name']);
$description = $conn->real_escape_string($_POST['description']);
$category    = $conn->real_escape_string($_POST['category']);
$max         = $_POST['max_members'] !== '' ? intval($_POST['max_members']) : 0;

/* Get the admin's id from the admin table */
$admin_row = $conn->query("SELECT id FROM admin WHERE username='{$conn->real_escape_string($_SESSION['admin'])}'")->fetch_assoc();
$admin_id  = $admin_row ? $admin_row['id'] : 1;

$sql = "INSERT INTO `groups` (name, description, category, max_members, created_by)
        VALUES ('$name', '$description', '$category', $max, $admin_id)";

$conn->query($sql);

header("Location: admin_dashboard.php?created=1");
exit();
?>
