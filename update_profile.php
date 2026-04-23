<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user']['id'];

$name        = $conn->real_escape_string($_POST['name']);
$email       = $conn->real_escape_string($_POST['email']);
$branch      = $conn->real_escape_string($_POST['branch']);
$year        = $conn->real_escape_string($_POST['year']);
$gender      = $conn->real_escape_string($_POST['gender']);
$bio         = $conn->real_escape_string($_POST['bio']);
$skills      = $conn->real_escape_string($_POST['skills']);
$interests   = $conn->real_escape_string($_POST['interests']);
$looking_for = $conn->real_escape_string($_POST['looking_for']);
$linkedin    = $conn->real_escape_string($_POST['linkedin']);
$portfolio   = $conn->real_escape_string($_POST['portfolio']);

/* PHOTO UPLOAD — only update if a new file was chosen */
$photo_sql = "";
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK && $_FILES['photo']['size'] > 0) {
    $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = "uploads/" . time() . "_" . $id . "." . $ext;
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filename)) {
        $photo_sql = ", photo='$filename'";
    }
}

$sql = "UPDATE users SET
    name        = '$name',
    email       = '$email',
    branch      = '$branch',
    year        = '$year',
    gender      = '$gender',
    bio         = '$bio',
    skills      = '$skills',
    interests   = '$interests',
    looking_for = '$looking_for',
    linkedin    = '$linkedin',
    portfolio   = '$portfolio'
    $photo_sql
WHERE id = $id";

$conn->query($sql);

/* REFRESH SESSION */
$_SESSION['user']['name']  = $name;
$_SESSION['user']['email'] = $email;

header("Location: profile.php?success=1");
exit();
?>
