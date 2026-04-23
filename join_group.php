<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$group_id = $_GET['id'];

/* CHECK IF ALREADY JOINED */
$check = $conn->query("SELECT * FROM group_members WHERE user_id=$user_id AND group_id=$group_id");

if($check->num_rows > 0){
    header("Location: group_details.php?id=$group_id");
    exit();
}

/* GET GROUP INFO */
$group = $conn->query("SELECT * FROM groups WHERE id=$group_id")->fetch_assoc();

/* COUNT MEMBERS */
$count = $conn->query("SELECT COUNT(*) as total FROM group_members WHERE group_id=$group_id")->fetch_assoc()['total'];

/* CHECK LIMIT */
if($group['max_members'] != 0 && $count >= $group['max_members']){
    header("Location: groups.php?error=full");
    exit();
}

/* INSERT */
$conn->query("INSERT INTO group_members (user_id, group_id) VALUES ($user_id, $group_id)");

/* REDIRECT */
header("Location: group_details.php?id=$group_id");
exit();
?>
