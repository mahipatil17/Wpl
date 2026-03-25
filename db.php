<?php
$conn = new mysqli("localhost", "root", "", "wpl_proj");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>