<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
if ($_SESSION['user']['profile_completed'] == 1) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Complete Profile | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { display: flex; min-height: 100vh; background: linear-gradient(135deg, #f0f1f7 0%, #e8eaf6 100%); }
    .setup-wrap { max-width: 640px; margin: 40px auto; padding: 0 20px; width: 100%; }
    .setup-header { text-align: center; margin-bottom: 28px; }
    .setup-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--indigo); display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 14px; }
    .setup-header h1 { font-size: 22px; font-weight: 600; }
    .setup-header p { font-size: 13.5px; color: var(--muted); margin-top: 4px; }
    .setup-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 32px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
    .step-bar { display: flex; gap: 6px; margin-bottom: 28px; }
    .step-dot { flex: 1; height: 4px; border-radius: 999px; background: #e5e7eb; }
    .step-dot.done { background: var(--indigo); }
    .form-section-title { font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 14px; margin-top: 20px; padding-bottom: 8px; border-bottom: 1px solid var(--border); }
    .form-section-title:first-child { margin-top: 0; }
    .skip-note { text-align: center; margin-top: 14px; font-size: 12.5px; color: var(--muted); }
    .skip-note a { color: var(--indigo); }
  </style>
</head>
<body>

<div class="setup-wrap">
  <div class="setup-header">
    <div class="setup-icon">🎓</div>
    <h1>Complete Your Profile</h1>
    <p>This helps others discover you on FY Connect</p>
  </div>

  <div class="setup-card">
    <div class="step-bar">
      <div class="step-dot done"></div>
      <div class="step-dot done"></div>
      <div class="step-dot"></div>
    </div>

    <form method="POST" action="save_profile.php" enctype="multipart/form-data">

      <div class="form-section-title">Basic Info</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Name</label>
          <input class="form-input" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input class="form-input" name="email" type="email" value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Branch</label>
          <input class="form-input" name="branch" placeholder="e.g. Computer Science" value="<?php echo htmlspecialchars($_SESSION['user']['branch'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Year</label>
          <select class="form-select" name="year">
            <?php foreach(['First Year','Second Year','Third Year','Final Year'] as $y): ?>
              <option <?php echo ($_SESSION['user']['year'] ?? '') === $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Gender</label>
          <select class="form-select" name="gender">
            <option>Male</option><option>Female</option><option>Prefer not to say</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Profile Photo (optional)</label>
          <input type="file" name="photo" accept="image/*" class="form-input" style="padding:6px">
        </div>
      </div>

      <div class="form-section-title">About You</div>
      <div class="form-group">
        <label class="form-label">Bio</label>
        <textarea class="form-textarea" name="bio" placeholder="Tell others about yourself..."></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Skills</label>
          <input class="form-input" name="skills" placeholder="e.g. Python, React, Figma">
        </div>
        <div class="form-group">
          <label class="form-label">Interests</label>
          <input class="form-input" name="interests" placeholder="e.g. Hackathons, Music">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Looking For</label>
        <select class="form-select" name="looking_for">
          <option>Hackathon Team</option><option>Travel Buddy</option><option>Hostel Friends</option><option>Roommate</option><option>Networking</option>
        </select>
      </div>

      <div class="form-section-title">Links (optional)</div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">LinkedIn</label>
          <input class="form-input" name="linkedin" placeholder="https://linkedin.com/in/...">
        </div>
        <div class="form-group">
          <label class="form-label">Portfolio / GitHub</label>
          <input class="form-input" name="portfolio" placeholder="https://yoursite.com">
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;justify-content:center">Save Profile &amp; Continue →</button>
    </form>

    <p class="skip-note"><a href="dashboard.php">Skip for now →</a></p>
  </div>
</div>

</body>
</html>
