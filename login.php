<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FY Connect - Login</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet"/>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Inter", sans-serif; }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #e9ecff, #f7f9ff);
    }

    .container { width: 100%; max-width: 520px; text-align: center; }

    .logo-circle {
      width: 56px; height: 56px; border-radius: 50%;
      background: linear-gradient(135deg, #4f7cff, #6a5cff);
      margin: 0 auto 10px;
      display: flex; align-items: center; justify-content: center;
      color: white; font-size: 22px;
      box-shadow: 0 10px 25px rgba(79, 124, 255, 0.35);
    }

    .title { font-weight: 700; font-size: 22px; }
    .subtitle { color: #666; font-size: 14px; margin-bottom: 20px; }

    .card {
      background: rgba(255, 255, 255, 0.55);
      backdrop-filter: blur(14px);
      border-radius: 16px;
      padding: 26px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .tabs {
      display: flex;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 999px;
      padding: 4px;
      margin-bottom: 18px;
    }

    .tab {
      flex: 1;
      padding: 8px;
      border-radius: 999px;
      font-size: 14px;
      cursor: pointer;
    }

    .tab.active {
      background: white;
      font-weight: 600;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .form { text-align: left; }
    .form h3 { margin-bottom: 4px; }
    .form small { color: #777; }

    .input-group { margin-top: 14px; }

    .input-group label {
      font-size: 13px;
      display: block;
      margin-bottom: 4px;
    }

    .input-group input,
    .input-group select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid rgba(0, 0, 0, 0.15);
      font-size: 14px;
      background: rgba(255, 255, 255, 0.75);
    }

    .btn {
      width: 100%;
      margin-top: 18px;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(135deg, #4f7cff, #6a5cff);
      color: white;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 12px 30px rgba(79, 124, 255, 0.4);
    }

    .message {
      margin-top: 10px;
      font-size: 13px;
      text-align: center;
    }

    .error { color: red; }
    .success { color: green; }

    #registerForm { display: none; }
  </style>
</head>

<body>

<div class="container">
  <div class="logo-circle">🎓</div>
  <div class="title">FY Connect</div>
  <div class="subtitle">Connect, Collaborate, and Build Networks</div>

  <div class="card">

    <div class="tabs">
      <div class="tab active" onclick="showLogin()">Login</div>
      <div class="tab" onclick="showRegister()">Register</div>
    </div>

    <form class="form" id="loginForm" action="login_process.php" method="POST">
      <h3>Welcome Back</h3>
      <small>Login to your account</small>

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required />
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required />
      </div>

      <button type="submit" class="btn">Login</button>
    </form>

    <form class="form" id="registerForm" action="register_process.php" method="POST" onsubmit="return validateName()">
      <h3>Create Account</h3>

      <div class="input-group">
        <label>Name</label>
        <input type="text" id="name" name="name" placeholder="Enter name" required />
        <small id="nameError" style="color:red; display:none;">Only letters allowed</small>
      </div>

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required />
      </div>

      <div class="input-group">
        <label>Branch</label>
        <select name="branch">
          <option <?php if(isset($user['branch']) && $user['branch']=="Computer Science") echo "selected"; ?>>Computer Science</option>
          <option <?php if(isset($user['branch']) && $user['branch']=="Electronics") echo "selected"; ?>>Electronics</option>
          <option <?php if(isset($user['branch']) && $user['branch']=="Information Technology") echo "selected"; ?>>Information Technology</option>
          <option <?php if(isset($user['branch']) && $user['branch']=="CBCS") echo "selected"; ?>>CBCS</option>
        </select>
      </div>

      <div class="input-group">
        <label>College</label>
        <select name="college">
          <option>KJ Somaiya College of Engineering</option>
          <option>VJTI Mumbai</option>
          <option>SPIT Mumbai</option>
          <option>DJ Sanghvi College of Engineering</option>
          <option>Thadomal Shahani Engineering College</option>
        </select>
      </div>

      <div class="input-group">
        <label>Year</label>
        <select name="year">
          <option>First Year</option>
          <option>Second Year</option>
          <option>Third Year</option>
          <option>Final Year</option>
        </select>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required onkeyup="checkPasswordStrength()" />
        <small id="passwordHint" style="font-size:12px;"></small>
      </div>

      <button type="submit" class="btn">Register</button>
    </form>

    <div class="message">
      <?php
      if (isset($_GET['error'])) {
        echo "<p class='error'>Invalid login credentials!</p>";
      }
      if (isset($_GET['success'])) {
        echo "<p class='success'>Registration successful! Please login.</p>";
      }
      ?>
    </div>

  </div>
</div>

<script>
function showRegister() {
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("registerForm").style.display = "block";
  document.querySelectorAll(".tab")[0].classList.remove("active");
  document.querySelectorAll(".tab")[1].classList.add("active");
}

function showLogin() {
  document.getElementById("registerForm").style.display = "none";
  document.getElementById("loginForm").style.display = "block";
  document.querySelectorAll(".tab")[1].classList.remove("active");
  document.querySelectorAll(".tab")[0].classList.add("active");
}

function validateName() {
  let name = document.getElementById("name").value;
  let regex = /^[A-Za-z ]+$/;

  if (!regex.test(name)) {
    document.getElementById("nameError").style.display = "block";
    return false;
  }

  document.getElementById("nameError").style.display = "none";
  return true;
}

function checkPasswordStrength() {
  let password = document.getElementById("password").value;
  let hint = document.getElementById("passwordHint");

  let strongRegex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/;

  if (password.length === 0) {
    hint.innerHTML = "";
  } 
  else if (password.length < 6) {
    hint.style.color = "red";
    hint.innerHTML = "Weak: Too short";
  } 
  else if (!strongRegex.test(password)) {
    hint.style.color = "orange";
    hint.innerHTML = "Medium: Add uppercase, number & symbol";
  } 
  else {
    hint.style.color = "green";
    hint.innerHTML = "Strong password";
  }
}
</script>

</body>
</html>
