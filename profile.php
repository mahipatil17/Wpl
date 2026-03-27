<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
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

<!-- ================= NAVBAR ================= -->
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
    <a href="discover.php">Find Students</a>
    <a href="groups.php">Groups</a>
    <a href="profile.php" class="active">My Profile</a>
  </div>

  <a href="logout.php">
    <button class="create-btn">Logout</button>
  </a>

</nav>



<!-- ================= PROFILE SECTION ================= -->
<section class="profile-section">

  <!-- PROFILE FORM CARD -->
  <div class="profile-card">

    <div class="profile-header">
      <div class="profile-pic"></div>
      <div>
        <h2>My Profile</h2>
        <p class="subtext">Update your personal information</p>
      </div>
    </div>

    <form id="profileForm">

      <div class="form-row">

        <div class="form-group">
          <label>Name</label>
          <input type="text" id="name" value="<?php echo $user['name']; ?>">
          <small class="error" id="nameError"></small>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="text" id="email" value="<?php echo $user['email']; ?>">
          <small class="error" id="emailError"></small>
        </div>

      </div>


      <div class="form-row">

        <div class="form-group">
          <label>Branch</label>
          <select>
            <option <?php if($user['branch']=="Computer Science") echo "selected"; ?>>Computer Science</option>
            <option <?php if($user['branch']=="Electronics") echo "selected"; ?>>Electronics</option>
            <option <?php if($user['branch']=="Information Technology") echo "selected"; ?>>Information Technology</option>
            <option <?php if($user['branch']=="CBCS") echo "selected"; ?>>CBCS</option>
          </select>
        </div>

        <div class="form-group">
          <label>Year</label>
          <select id="yearSelect">
            <option <?php if($user['year']=="First Year") echo "selected"; ?>>First Year</option>
            <option <?php if($user['year']=="Second Year") echo "selected"; ?>>Second Year</option>
            <option <?php if($user['year']=="Third Year") echo "selected"; ?>>Third Year</option>
            <option <?php if($user['year']=="Final Year") echo "selected"; ?>>Final Year</option>
          </select>
        </div>

      </div>


      <div class="form-row">
        <div class="form-group">
          <label>Gender</label>
          <select>
            <option>Male</option>
            <option>Female</option>
            <option>Do not prefer to say</option>
          </select>
        </div>
      </div>


      <div class="form-group full">
        <label>Address</label>
        <input type="text" placeholder="Enter your address">
      </div>

      <div class="btn-group">
        <button type="submit" class="save-btn">Save Changes</button>
      </div>

    </form>

  </div>



  <!-- ================= JOINED GROUPS ================= -->
  <div class="profile-card joined-section">

    <h2>Joined Clubs</h2>

    <div class="joined-groups" id="joinedGroups">
      <!-- JS will handle content -->
    </div>

  </div>

</section>


<script src="profile.js"></script>

</body>
</html>