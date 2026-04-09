<?php
include("db.php");
session_start();

$error = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT interests_selected FROM users WHERE id = $user_id");
$row = mysqli_fetch_assoc($result);

if ($row['interests_selected'] == 1) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['save'])) {

    if (empty($_POST['interests'])) {
        $error = "Please select at least 1 interest.";
    } 
    else if (count($_POST['interests']) > 5) {
        $error = "You can select maximum 5 interests only.";
    } 
    else {

        mysqli_query($conn, "DELETE FROM user_interests WHERE user_id = $user_id");

        foreach ($_POST['interests'] as $interest) {
            mysqli_query($conn, "INSERT INTO user_interests (user_id, interest) VALUES ($user_id, '$interest')");
        }

        mysqli_query($conn, "UPDATE users SET interests_selected = 1 WHERE id = $user_id");

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Interests</title>
    <link rel="stylesheet" href="interests.css">
</head>

<body>

<div class="container">
    <h2>Select Your Interests</h2>
    <p class="subtitle">Choose up to 5 interests to personalize your experience</p>

    <?php if ($error != "") { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <div class="card">

            <label><input type="checkbox" name="interests[]" value="Coding"> Coding</label>
            <label><input type="checkbox" name="interests[]" value="Hackathons"> Hackathons</label>
            <label><input type="checkbox" name="interests[]" value="Clubs & Societies"> Clubs & Societies</label>
            <label><input type="checkbox" name="interests[]" value="Startups"> Startups</label>
            <label><input type="checkbox" name="interests[]" value="Internships"> Internships</label>
            <label><input type="checkbox" name="interests[]" value="Workshops"> Workshops</label>
            <label><input type="checkbox" name="interests[]" value="Tech Talks"> Tech Talks</label>

            <button type="submit" name="save" class="btn">
                Continue (Select up to 5)
            </button>

        </div>

    </form>
</div>

</body>
</html>