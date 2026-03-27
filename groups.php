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
<title>Groups | FY Connect</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="groups.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
  <div class="nav-left">
    <div class="logo-box">🎓</div>
    <h3>FY Connect</h3>
  </div>

  <div class="nav-center">
    <a href="dashboard.php">Dashboard</a>
    <a href="discover.php">Discover</a>
    <a class="active">Groups</a>
    <a href="profile.php">My Profile</a>
  </div>

  <div class="nav-right">
    <span class="email"><?php echo $_SESSION['user']['email']; ?></span>
    <a href="logout.php"><button class="login-btn">Logout</button></a>
  </div>
</nav>

<!-- ================= HEADER ================= -->
<section class="page-header">
  <h1>Groups & Communities</h1>
  <p>Join groups to connect with like-minded peers</p>

  <div class="tabs">
    <button class="tab active">All</button>
    <button class="tab">My Groups</button>
    <button class="tab">Interests</button>
    <button class="tab">Hostel</button>
    <button class="tab">Travel</button>
    <button class="tab">Events</button>
  </div>
</section>

<!-- ================= INTEREST GROUPS ================= -->
<section class="group-section">
  <h2>Interest-Based Groups</h2>

  <div class="groups-grid">
    <div class="group-card">
      <h3>Hackathon Warriors</h3>
      <p>Team up for hackathons and coding competitions.</p>
      <div class="meta">
        <span>4 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>

    <div class="group-card">
      <h3>Web Development Club</h3>
      <p>Learn and build modern web applications together.</p>
      <div class="meta">
        <span>3 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>

    <div class="group-card">
      <h3>Machine Learning Club</h3>
      <p>Explore AI/ML research and practical projects.</p>
      <div class="meta">
        <span>2 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>
  </div>
</section>

<!-- ================= HOSTEL GROUPS ================= -->
<section class="group-section">
  <h2>Hostel Groups</h2>

  <div class="groups-grid">
    <div class="group-card">
      <h3>Block A Hostel</h3>
      <p>Connect with students from Block A.</p>
      <div class="meta">
        <span>2 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>

    <div class="group-card">
      <h3>Block B Hostel</h3>
      <p>Community for Block B residents.</p>
      <div class="meta">
        <span>3 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>
  </div>
</section>

<!-- ================= TRAVEL GROUPS ================= -->
<section class="group-section">
  <h2>Travel & Carpool Groups</h2>

  <div class="groups-grid">
    <div class="group-card">
      <h3>Andheri Route</h3>
      <p>Students commuting daily from Andheri.</p>
      <div class="meta">
        <span>4 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>

    <div class="group-card">
      <h3>Dadar Route</h3>
      <p>Daily travel group from Dadar area.</p>
      <div class="meta">
        <span>3 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>
  </div>
</section>

<!-- ================= EVENT GROUPS ================= -->
<section class="group-section">
  <h2>Event Groups</h2>

  <div class="groups-grid">
    <div class="group-card">
      <h3>Hackathon Team – March</h3>
      <p>Prepare for March Hackathon competition.</p>
      <div class="meta">
        <span>5 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>

    <div class="group-card">
      <h3>Tech Fest 2026</h3>
      <p>Collaboration group for annual tech fest.</p>
      <div class="meta">
        <span>4 members</span>
        <button class="join-btn">Join</button>
      </div>
    </div>
  </div>
</section>

</body>
</html>