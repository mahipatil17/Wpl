<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

$id = $_SESSION['user']['id'];
$v  = intval($_GET['v'] ?? 1);
$v  = ($v === 1) ? 1 : 0;

$conn->query("UPDATE users SET visibility=$v WHERE id=$id");
$_SESSION['user']['visibility'] = $v;

echo "ok";
?>
