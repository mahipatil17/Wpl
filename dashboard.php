<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | FY Connect</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="dashboard.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
  <div class="nav-left">
    <div class="logo-box">🎓</div>
    <h3>FY Connect</h3>
  </div>

  <div class="nav-center">
    <a class="active" href="dashboard.php">Dashboard</a>
    <a href="discover.php">Discover</a>
    <a href="groups.php">Groups</a>
    <a href="profile.php">My Profile</a>
  </div>

  <div class="nav-right">
    <span class="email"><?php echo $_SESSION['user']['email']; ?></span>
    <a href="logout.php"><button class="login-btn">Logout</button></a>
  </div>
</nav>

<!-- ================= WELCOME SECTION ================= -->
<section class="welcome">
  <h1>Welcome back, <?php echo $_SESSION['user']['name']; ?></h1>
  <p>Here’s what’s happening in your FY community</p>
</section>

<!-- ================= STATS ================= -->
<section class="stats">

  <div class="stat-card">
    <h2>3</h2>
    <p>Groups Joined</p>
  </div>

  <div class="stat-card">
    <h2>18</h2>
    <p>Connections Made</p>
  </div>

  <div class="stat-card">
    <h2>2</h2>
    <p>Upcoming Events</p>
  </div>

  <div class="stat-card">
    <h2>24/7</h2>
    <p>Platform Access</p>
  </div>

</section>

<!-- ================= MAIN CONTENT ================= -->
<section class="main-content">

  <div class="card">
    <h2>My Groups</h2>

    <div class="group-item">
      <h4>Hackathon Warriors</h4>
      <span>8 Members</span>
    </div>

    <div class="group-item">
      <h4>Web Development Club</h4>
      <span>5 Members</span>
    </div>

    <a href="groups.php" class="view-link">View All Groups →</a>
  </div>

  <div class="card">
    <h2>Suggested Groups</h2>

    <div class="group-item">
      <h4>Machine Learning Club</h4>
      <button class="join-btn">Join</button>
    </div>

    <div class="group-item">
      <h4>Tech Fest 2026</h4>
      <button class="join-btn">Join</button>
    </div>

  </div>

</section>

<!-- ================= EVENTS ================= -->
<section class="events">
  <h2>Upcoming Events</h2>

  <div class="events-grid">

    <div class="event-card">
      <h3>Hackathon – March 15</h3>
      <p>Form your teams and get ready for the annual coding hackathon.</p>
    </div>

    <div class="event-card">
      <h3>College Cultural Fest</h3>
      <p>Music, dance and drama competitions across all departments.</p>
    </div>

  </div>
</section>

</body>
</html>