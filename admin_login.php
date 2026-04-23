<?php
session_start();
if (isset($_SESSION['admin'])) { header("Location: admin_dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | FY Connect</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
  font-family: 'DM Sans', sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3f4f6; /* light grey background */
}

.card {
  background: #ffffff; /* pure white */
  border-radius: 16px;
  padding: 40px;
  width: 360px;
  text-align: center;

  /* 3D shadow effect */
  box-shadow: 
    0 10px 25px rgba(0, 0, 0, 0.1),
    0 20px 40px rgba(0, 0, 0, 0.08);
}

/* Remove glass effect */
.admin-badge {
  display: none; /* optional */
}

h2 {
  font-family: 'Sora', sans-serif;
  font-size: 22px;
  color: #111827;
  margin-bottom: 6px;
}

p {
  font-size: 13px;
  color: #6b7280;
  margin-bottom: 28px;
}

label {
  display: block;
  font-size: 11.5px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 5px;
  text-align: left;
}

input {
  width: 100%;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  background: #fff;
  font-size: 13.5px;
  color: #111827;
}

input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99,102,241,0.2);
}

button {
  width: 100%;
  padding: 11px;
  border-radius: 8px;
  background: #4f46e5;
  color: white;
  border: none;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  margin-top: 6px;
}

button:hover {
  background: #4338ca;
}

.err {
  background: #fee2e2;
  border: 1px solid #fecaca;
  color: #b91c1c;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 13px;
  margin-top: 14px;
}

.back {
  margin-top: 20px;
  font-size: 13px;
  color: #6b7280;
}

.back a {
  color: #4f46e5;
  text-decoration: none;
}
  </style>
</head>
<body>
<div class="card">
  <h2>Admin Login</h2>
  <p>Restricted access — admin only</p>

  <form method="POST" action="admin_login_process.php">
    <div class="field">
      <label>Username</label>
      <input type="text" name="username" placeholder="admin" required autocomplete="username">
    </div>
    <div class="field">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
    </div>
    <button type="submit">Login to Admin Panel</button>
  </form>

  <?php if(isset($_GET['error'])): ?>
    <div class="err">Invalid credentials. Please try again.</div>
  <?php endif; ?>

  <p class="back"><a href="landing3.html">Back to Home</a></p>
</div>
</body>
</html>
