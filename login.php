<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$error        = $_GET['error']   ?? '';
$email_cookie = htmlspecialchars($_COOKIE['user_email'] ?? '');
$tab     = isset($_GET['register']) ? 'register' : 'login';
$db_err  = $_SESSION['reg_error'] ?? '';
unset($_SESSION['reg_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FY Connect — Login / Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
    body{font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;background:#f0f1f7;}

    .left{
      width:420px;flex-shrink:0;
      background:linear-gradient(140deg,#3730a3 0%,#5046e5 55%,#3b82f6 100%);
      display:flex;flex-direction:column;align-items:center;justify-content:center;
      padding:60px 44px;text-align:center;color:#fff;position:relative;overflow:hidden;
    }
    .left{
  width:420px;
  flex-shrink:0;
  background:#5046e5; /* plain purple */
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  padding:60px 44px;
  text-align:center;
  color:#fff;
  position:relative;
  overflow:hidden;
}
    .left-logo{width:60px;height:60px;border-radius:18px;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 20px;}
    .left h1{font-family:'Sora',sans-serif;font-size:28px;font-weight:700;line-height:1.25;margin-bottom:12px;}
    .left p{font-size:14px;color:rgba(255,255,255,0.72);line-height:1.7;max-width:300px;}
    .feats{margin-top:36px;display:flex;flex-direction:column;gap:11px;text-align:left;}
    .feat{display:flex;align-items:center;gap:10px;font-size:13.5px;color:rgba(255,255,255,0.85);}
    .feat-dot{width:20px;height:20px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;}

    .right{flex:1;overflow-y:auto;display:flex;align-items:flex-start;justify-content:center;padding:40px 32px;}
    .form-box{width:100%;max-width:560px;}

    .tabs{display:flex;background:#eef0f6;border-radius:12px;padding:4px;margin-bottom:28px;}
    .tab{flex:1;padding:9px;border-radius:9px;font-size:14px;font-weight:500;cursor:pointer;text-align:center;transition:all .2s;color:#6b7280;border:none;background:none;font-family:'DM Sans',sans-serif;}
    .tab.active{background:#fff;color:#1a1829;box-shadow:0 1px 5px rgba(0,0,0,0.1);}

    .steps{display:flex;gap:6px;margin-bottom:24px;}
    .step-bar{flex:1;height:4px;border-radius:999px;background:#e5e7eb;transition:background .3s;}
    .step-bar.active{background:#5046e5;}

    .sec-label{font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;margin-top:22px;padding-bottom:8px;border-bottom:1px solid #e5e7eb;}
    .sec-label:first-of-type{margin-top:0;}

    .row2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
    .field{margin-bottom:14px;}
    label{display:block;font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;}
    input,select,textarea{width:100%;padding:10px 13px;border-radius:8px;border:1px solid rgba(0,0,0,.13);background:#f9fafb;font-size:13.5px;font-family:'DM Sans',sans-serif;color:#1a1829;transition:border-color .15s,box-shadow .15s;}
    input:focus,select:focus,textarea:focus{outline:none;border-color:#5046e5;box-shadow:0 0 0 3px rgba(80,70,229,.12);background:#fff;}
    textarea{resize:vertical;min-height:72px;}

    /* validation hint text */
    .hint{font-size:12px;margin-top:4px;min-height:16px;display:block;}
    .hint-err{color:#dc2626;}
    .hint-warn{color:#d97706;}
    .hint-ok{color:#16a34a;}

    .btn-row{display:flex;gap:10px;margin-top:8px;}
    .btn-primary{flex:1;padding:11px;border:none;border-radius:8px;background:#5046e5;color:#fff;font-size:14.5px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;}
    .btn-primary:hover{background:#3730a3;transform:translateY(-1px);box-shadow:0 6px 20px rgba(80,70,229,.35);}
    .btn-secondary{padding:11px 18px;border:1px solid #d1d5db;border-radius:8px;background:#fff;color:#374151;font-size:14px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;}
    .btn-secondary:hover{background:#f3f4f6;}

    .msg{padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;}
    .msg-err{background:#fee2e2;color:#dc2626;}
    .msg-ok{background:#dcfce7;color:#16a34a;}

    .back-link{text-align:center;margin-top:18px;font-size:13px;color:#6b7280;}
    .back-link a{color:#5046e5;font-weight:500;}
    .form-title{font-family:'Sora',sans-serif;font-size:22px;font-weight:600;margin-bottom:3px;}
    .form-sub{font-size:13.5px;color:#6b7280;margin-bottom:20px;}
    .hidden{display:none!important;}

    @media(max-width:768px){.left{display:none;}.right{padding:28px 18px;}}

    .pw-wrap{position:relative;}
.pw-wrap input{padding-right:42px;}
.pw-toggle{
  position:absolute;right:12px;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;padding:4px;
  color:#9ca3af;display:flex;align-items:center;
}
.pw-toggle:hover{color:#5046e5;}
.pw-toggle:hover{color:#5046e5;}
  </style>
</head>
<body>

<!-- LEFT -->
<div class="left">
  <div class="left-logo">🎓</div>
  <h1>Welcome to<br>FY Connect</h1>
  <p>Connect with fellow first-year students, join communities, and build lasting networks.</p>
  <div class="feats">
    <div class="feat"><div class="feat-dot">✓</div> Discover students by branch &amp; skills</div>
    <div class="feat"><div class="feat-dot">✓</div> Join groups and communities</div>
    <div class="feat"><div class="feat-dot">✓</div> Stay updated on campus events</div>
    <div class="feat"><div class="feat-dot">✓</div> Build your professional profile</div>
  </div>
</div>

<!-- RIGHT -->
<div class="right">
<div class="form-box">

  <div class="tabs">
    <button class="tab <?php echo $tab==='login'?'active':''; ?>" onclick="switchTab('login')">Login</button>
    <button class="tab <?php echo $tab==='register'?'active':''; ?>" onclick="switchTab('register')">Register</button>
  </div>

  <!-- LOGIN -->
  <div id="loginSection" class="<?php echo $tab==='register'?'hidden':''; ?>">
    <div class="form-title">Welcome back</div>
    <div class="form-sub">Login to your student account</div>

    <?php if($error && $tab==='login'): ?>
      <div class="msg msg-err">Invalid email or password. Please try again.</div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" placeholder="you@example.com" value="<?php echo $email_cookie; ?>" required>
      </div>
      <div class="field">
        <label>Password</label>
        <div class="pw-wrap">
  <input type="password" name="password" id="lp" placeholder="Enter password" required>
  <button type="button" class="pw-toggle" onclick="togglePw('lp',this)" title="Show password">
    <svg id="lp-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
    <svg id="lp-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
  </button>
</div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin:10px 0 4px;">
        <input type="checkbox" name="remember" id="rememberMe" style="width:16px;height:16px;accent-color:#5046e5;cursor:pointer;" <?php echo $email_cookie ? 'checked' : ''; ?>>
        <label for="rememberMe" style="font-size:13px;color:#6b7280;text-transform:none;letter-spacing:0;font-weight:400;cursor:pointer;margin-bottom:0;">Remember my email for 7 days</label>
      </div>
      <button type="submit" class="btn-primary" style="width:100%;margin-top:4px">Login</button>
    </form>
  </div>

  <!-- REGISTER -->
  <div id="registerSection" class="<?php echo $tab==='login'?'hidden':''; ?>">

    <div class="steps">
      <div class="step-bar active" id="bar1"></div>
      <div class="step-bar" id="bar2"></div>
    </div>

    <?php if($error === 'email_taken'): ?>
      <div class="msg msg-err">That email is already registered. Try logging in.</div>
    <?php elseif($error === 'register_failed'): ?>
      <div class="msg msg-err">Registration failed<?php echo $db_err ? ': '.$db_err : '. Please try again.'; ?></div>
    <?php elseif($error === 'missing_fields'): ?>
      <div class="msg msg-err">Please fill in all required fields.</div>
    <?php endif; ?>

    <!-- STEP 1 -->
    <div id="step1">
      <div class="form-title">Create account</div>
      <div class="form-sub">Step 1 of 2 — Basic info</div>

      <div class="sec-label">Account Details</div>

      <!-- Name + Email -->
      <div class="row2">
        <div class="field">
          <label>Full Name *</label>
          <input type="text" id="r_name" placeholder="Your full name"
                 oninput="validateName(this)">
          <span class="hint" id="name_hint"></span>
        </div>
        <div class="field">
          <label>Email *</label>
          <input type="email" id="r_email" placeholder="you@example.com">
        </div>
      </div>

      <!-- College (full width) -->
      <div class="field">
        <label>College</label>
        <select id="r_college">
          <option value="">— Select your college —</option>
          <option>KJ Somaiya College of Engineering</option>
          <option>VJTI Mumbai</option>
          <option>SPIT Mumbai</option>
          <option>DJ Sanghvi College of Engineering</option>
          <option>Thadomal Shahani Engineering College</option>
        </select>
      </div>

      <!-- Branch + Year -->
      <div class="row2">
        <div class="field">
          <label>Branch</label>
          <select id="r_branch">
            <option value="">— Select branch —</option>
            <option>Computer Science</option>
            <option>Information Technology</option>
            <option>AIDS</option>
            <option>EXTC</option>
          </select>
        </div>
        <div class="field">
          <label>Year</label>
          <select id="r_year">
            <option>First Year</option>
            <option>Second Year</option>
            <option>Third Year</option>
            <option>Final Year</option>
          </select>
        </div>
      </div>

      <!-- Gender + Password -->
      <div class="row2">
        <div class="field">
          <label>Gender</label>
          <select id="r_gender">
            <option>Male</option>
            <option>Female</option>
            <option>Prefer not to say</option>
          </select>
        </div>
        <div class="field">
          <label>Password *</label>
          <div class="pw-wrap">
  <input type="password" id="r_password" placeholder="Min 8 chars + 1 number" oninput="validatePassword(this)">
  <button type="button" class="pw-toggle" onclick="togglePw('r_password',this)" title="Show password">
    <svg id="r_password-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
    <svg id="r_password-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
  </button>
</div>
          <span class="hint" id="pass_hint"></span>
        </div>
      </div>

      <div class="btn-row">
        <button type="button" class="btn-primary" onclick="goStep2()">Next: Profile Details </button>
      </div>
      <p id="step1-err" style="color:#dc2626;font-size:12.5px;margin-top:10px;display:none"></p>
    </div>

    <!-- STEP 2 -->
    <div id="step2" class="hidden">
      <div class="form-title">Complete your profile</div>
      <div class="form-sub">Step 2 of 2 — Help others discover you</div>

      <form id="registerForm" action="register_process.php" method="POST" enctype="multipart/form-data">
        <!-- Hidden: all step-1 values -->
        <input type="hidden" name="name"     id="h_name">
        <input type="hidden" name="email"    id="h_email">
        <input type="hidden" name="college"  id="h_college">
        <input type="hidden" name="branch"   id="h_branch">
        <input type="hidden" name="year"     id="h_year">
        <input type="hidden" name="gender"   id="h_gender">
        <input type="hidden" name="password" id="h_password">

        <div class="sec-label">About You</div>
        <div class="field">
          <label>Bio</label>
          <textarea name="bio" placeholder="Tell others about yourself — what you're studying, what you're passionate about..."></textarea>
        </div>
        <div class="row2">
          <div class="field">
            <label>Skills</label>
            <input type="text" name="skills" placeholder="e.g. Python, React, Figma">
          </div>
          <div class="field">
            <label>Interests</label>
            <input type="text" name="interests" placeholder="e.g. Hackathons, Music, Travel">
          </div>
        </div>
        <div class="field">
          <label>Looking For</label>
          <select name="looking_for">
            <option>Hackathon Team</option>
            <option>Travel Buddy</option>
            <option>Hostel Friends</option>
            <option>Roommate</option>
            <option>Networking</option>
          </select>
        </div>

        <div class="sec-label">Links &amp; Photo</div>
        <div class="row2">
          <div class="field">
            <label>LinkedIn (optional)</label>
            <input type="text" name="linkedin" placeholder="https://linkedin.com/in/...">
          </div>
          <div class="field">
            <label>Portfolio / GitHub (optional)</label>
            <input type="text" name="portfolio" placeholder="https://yoursite.com">
          </div>
        </div>
        <div class="field">
          <label>Profile Photo (optional)</label>
          <input type="file" name="photo" accept="image/*" style="padding:7px">
        </div>

        <div class="btn-row">
          <button type="button" class="btn-secondary" onclick="goStep1()">Back</button>
          <button type="submit" class="btn-primary">Create Account</button>
        </div>
      </form>
    </div>

  </div><!-- /registerSection -->

  <p class="back-link"><a href="landing3.html"> Back to Home</a></p>
</div>
</div>

<script>
/* ── TAB SWITCH ── */
function switchTab(tab) {
  var isLogin = tab === 'login';
  document.getElementById('loginSection').classList.toggle('hidden', !isLogin);
  document.getElementById('registerSection').classList.toggle('hidden', isLogin);
  document.querySelectorAll('.tab').forEach(function(t,i){
    t.classList.toggle('active', isLogin ? i===0 : i===1);
  });
}

/* ── NAME VALIDATION ── */
function validateName(input) {
  var hint = document.getElementById('name_hint');
  var val  = input.value;
  if (val === '') { hint.textContent = ''; hint.className = 'hint'; return true; }
  if (/[^a-zA-Z\s]/.test(val)) {
    hint.textContent = 'Only letters allowed';
    hint.className   = 'hint hint-err';
    return false;
  }
  hint.textContent = '';
  hint.className   = 'hint';
  return true;
}

/* ── PASSWORD VALIDATION ── */
function validatePassword(input) {
  var hint = document.getElementById('pass_hint');
  var val  = input.value;
  if (val === '') { hint.textContent = ''; hint.className = 'hint'; return false; }
  if (val.length < 8) {
    hint.textContent = 'At least 8 characters required';
    hint.className   = 'hint hint-err';
    return false;
  }
  if (!/\d/.test(val)) {
    hint.textContent = 'Must include at least 1 number';
    hint.className   = 'hint hint-warn';
    return false;
  }
  hint.textContent = 'Strong password ✔';
  hint.className   = 'hint hint-ok';
  return true;
}

function goStep2() {
  var name    = document.getElementById('r_name').value.trim();
  var email   = document.getElementById('r_email').value.trim();
  var pass    = document.getElementById('r_password').value;
  var errEl   = document.getElementById('step1-err');

  var nameOk  = name !== '' && validateName(document.getElementById('r_name'));
  var emailOk = email !== '';
  var passOk  = validatePassword(document.getElementById('r_password'));

  if (!nameOk || !emailOk || !passOk) {
    var msg = [];
    if (!nameOk)  msg.push('valid name (letters only)');
    if (!emailOk) msg.push('email address');
    if (!passOk)  msg.push('valid password (8+ chars, 1 number)');
    errEl.textContent = 'Please fill all required fields correctly — missing: ' + msg.join(', ') + '.';
    errEl.style.display = 'block';
    return;
  }
  errEl.style.display = 'none';


  document.getElementById('h_name').value    = name;
  document.getElementById('h_email').value   = email;
  document.getElementById('h_college').value = document.getElementById('r_college').value;
  document.getElementById('h_branch').value  = document.getElementById('r_branch').value;
  document.getElementById('h_year').value    = document.getElementById('r_year').value;
  document.getElementById('h_gender').value  = document.getElementById('r_gender').value;
  document.getElementById('h_password').value = pass;

  document.getElementById('step1').classList.add('hidden');
  document.getElementById('step2').classList.remove('hidden');
  document.getElementById('bar2').classList.add('active');
  document.querySelector('.right').scrollTop = 0;
}

function goStep1() {
  document.getElementById('step2').classList.add('hidden');
  document.getElementById('step1').classList.remove('hidden');
  document.getElementById('bar2').classList.remove('active');
}
function togglePw(id, btn) {
  var input = document.getElementById(id);
  var eye    = document.getElementById(id + '-eye');
  var eyeOff = document.getElementById(id + '-eye-off');
  if (input.type === 'password') {
    input.type     = 'text';
    eye.style.display    = 'none';
    eyeOff.style.display = 'block';
  } else {
    input.type     = 'password';
    eye.style.display    = 'block';
    eyeOff.style.display = 'none';
  }
}

<?php if($tab === 'register'): ?>
switchTab('register');
<?php if($error): ?>goStep1();<?php endif; ?>
<?php endif; ?>
</script>
</body>
</html>
