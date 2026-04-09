<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$profile_pic = $_SESSION['user']['profile_pic'] ?? '';

if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){

    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));

    if(in_array($ext, $allowed)){

        $filename = uniqid() . "." . $ext;
        $destination = $upload_dir . $filename;

        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)){
            $profile_pic = $filename;
        }
    }
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$branch = $_POST['branch'] ?? '';
$year = $_POST['year'] ?? '';
$gender = $_POST['gender'] ?? '';
$address = $_POST['address'] ?? '';

$stmt = $conn->prepare("UPDATE users SET name=?, email=?, branch=?, year=?, gender=?, address=?, profile_pic=? WHERE id=?");
$stmt->bind_param("sssssssi", $name, $email, $branch, $year, $gender, $address, $profile_pic, $user_id);
$stmt->execute();

$_SESSION['user']['name'] = $name;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['branch'] = $branch;
$_SESSION['user']['year'] = $year;
$_SESSION['user']['gender'] = $gender;
$_SESSION['user']['address'] = $address;
$_SESSION['user']['profile_pic'] = $profile_pic;

header("Location: profile.php");
exit();
?>