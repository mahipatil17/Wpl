<?php
session_start();
include 'db.php';

$name        = trim($_POST['name']        ?? '');
$email       = trim($_POST['email']       ?? '');
$college     = trim($_POST['college']     ?? '');
$branch      = trim($_POST['branch']      ?? '');
$year        = trim($_POST['year']        ?? 'First Year');
$gender      = trim($_POST['gender']      ?? '');
$pass        = $_POST['password']         ?? '';
$bio         = trim($_POST['bio']         ?? '');
$skills      = trim($_POST['skills']      ?? '');
$interests   = trim($_POST['interests']   ?? '');
$looking_for = trim($_POST['looking_for'] ?? '');
$linkedin    = trim($_POST['linkedin']    ?? '');
$portfolio   = trim($_POST['portfolio']   ?? '');

/* Basic validation */
if ($name === '' || $email === '' || $pass === '') {
    header("Location: login.php?error=missing_fields&register=1");
    exit();
}

/* Duplicate email check */
$chk = $conn->prepare("SELECT id FROM users WHERE email = ?");
$chk->bind_param("s", $email);
$chk->execute();
$chk->store_result();
if ($chk->num_rows > 0) {
    header("Location: login.php?error=email_taken&register=1");
    exit();
}
$chk->close();

/* Hash password */
$hashed = password_hash($pass, PASSWORD_DEFAULT);

/* Handle photo upload */
$photo = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK && $_FILES['photo']['size'] > 0) {
    $ext   = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $fname = 'uploads/' . time() . '_' . rand(100,999) . '.' . $ext;
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $fname)) {
        $photo = $fname;
    }
}

/* Insert — includes college column */
$stmt = $conn->prepare(
    "INSERT INTO users
        (name, email, college, branch, year, gender, password,
         bio, skills, interests, looking_for, linkedin, portfolio, photo,
         profile_completed, visibility)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1)"
);
$stmt->bind_param(
    "ssssssssssssss",
    $name, $email, $college, $branch, $year, $gender, $hashed,
    $bio, $skills, $interests, $looking_for, $linkedin, $portfolio, $photo
);

if ($stmt->execute()) {
    $user_id = $conn->insert_id;
    $stmt->close();
    $row = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
    $_SESSION['user'] = $row;
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['reg_error'] = $conn->error;
    $stmt->close();
    header("Location: login.php?error=register_failed&register=1");
    exit();
}
