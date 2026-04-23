<?php
$current = basename($_SERVER['PHP_SELF']);
function nav_active($page, $current) {
    return ($current === $page) ? 'active' : '';
}
$user_name = $_SESSION['user']['name'] ?? '';
$user_email = $_SESSION['user']['email'] ?? '';
$initials = strtoupper(substr($user_name, 0, 1) . (strpos($user_name, ' ') !== false ? substr($user_name, strpos($user_name, ' ') + 1, 1) : ''));
?>
<nav class="navbar">
  <div class="nav-left">
    <div class="logo-box">🎓</div>
    <span class="logo-text">FY Connect</span>
  </div>
  <div class="nav-center">
    <a href="dashboard.php" class="nav-link <?php echo nav_active('dashboard.php', $current); ?>">Dashboard</a>
    <a href="discover.php" class="nav-link <?php echo nav_active('discover.php', $current); ?>">Discover</a>
    <a href="groups.php" class="nav-link <?php echo nav_active('groups.php', $current); ?>">Groups</a>
    <a href="profile.php" class="nav-link <?php echo nav_active('profile.php', $current); ?>">My Profile</a>
  </div>
  <div class="nav-right">
    <span class="nav-email"><?php echo htmlspecialchars($user_email); ?></span>
    <div class="avatar avatar-sm avatar-indigo"><?php echo htmlspecialchars($initials); ?></div>
    <a href="logout.php"><button class="btn btn-outline btn-sm">Logout</button></a>
  </div>
</nav>
