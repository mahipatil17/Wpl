<?php
$conn = new mysqli("localhost", "root", "", "fyconnect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>