<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

$profile_img = "uploads/default.png";
if (!empty($user['profile_pic']) && file_exists("uploads/" . $user['profile_pic'])) {
    $profile_img = "uploads/" . $user['profile_pic'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | FY Connect</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="profile.css">
</head>
<body>

<nav class="navbar">
  <div class="nav-left">
    <div class="logo-box">🎓</div>
    <div class="brand">
      <h3>FY Connect</h3>
      <p>Student Community</p>
    </div>
  </div>

  <div class="nav-center">
    <a href="dashboard.php">Dashboard</a>
    <a href="discover.php">Discover</a>
    <a href="groups.php">Groups</a>
    <a href="profile.php" class="active">My Profile</a>
  </div>

  <a href="logout.php">
    <button class="create-btn">Logout</button>
  </a>
</nav>

<section class="profile-section">

  <div class="profile-card">

    <div class="profile-header">
      <h2 class="profile-title">My Profile</h2>
      <div class="profile-pic">
        <img src="<?php echo $profile_img; ?>" alt="Profile">
      </div>
    </div>

    <form action="profile_process.php" method="POST" enctype="multipart/form-data">

      <div class="form-row">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" value="<?php echo $user['name'] ?? ''; ?>">
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="text" name="email" value="<?php echo $user['email'] ?? ''; ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Branch</label>
          <select name="branch">
            <option <?php if(($user['branch'] ?? '')=="Computer Science") echo "selected"; ?>>Computer Science</option>
            <option <?php if(($user['branch'] ?? '')=="Electronics") echo "selected"; ?>>Electronics</option>
            <option <?php if(($user['branch'] ?? '')=="Information Technology") echo "selected"; ?>>Information Technology</option>
            <option <?php if(($user['branch'] ?? '')=="CBCS") echo "selected"; ?>>CBCS</option>
          </select>
        </div>

        <div class="form-group">
          <label>Year</label>
          <select name="year">
            <option <?php if(($user['year'] ?? '')=="First Year") echo "selected"; ?>>First Year</option>
            <option <?php if(($user['year'] ?? '')=="Second Year") echo "selected"; ?>>Second Year</option>
            <option <?php if(($user['year'] ?? '')=="Third Year") echo "selected"; ?>>Third Year</option>
            <option <?php if(($user['year'] ?? '')=="Final Year") echo "selected"; ?>>Final Year</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Gender</label>
          <select name="gender">
            <option value="Male" <?php if(($user['gender'] ?? '')=="Male") echo "selected"; ?>>Male</option>
            <option value="Female" <?php if(($user['gender'] ?? '')=="Female") echo "selected"; ?>>Female</option>
            <option value="Do not prefer to say" <?php if(($user['gender'] ?? '')=="Do not prefer to say") echo "selected"; ?>>Do not prefer to say</option>
          </select>
        </div>

        <div class="form-group">
          <label>Profile Picture</label>
          <input type="file" name="profile_pic" accept="image/*">
        </div>
      </div>

      <div class="form-group full">
        <label>Address</label>
        <input type="text" name="address" value="<?php echo $user['address'] ?? ''; ?>">
      </div>

      <div class="btn-group">
        <button type="submit" class="save-btn">Save Changes</button>
      </div>

    </form>

  </div>

</section>

</body>
</html>
