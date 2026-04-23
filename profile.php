<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$id = $_SESSION['user']['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

$initials = strtoupper(substr($user['name'],0,1) . (strpos($user['name'],' ')!==false ? substr($user['name'],strpos($user['name'],' ')+1,1) : ''));

// Count groups
$groups_count = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE user_id=$id")->fetch_assoc()['t'];
$my_groups = $conn->query("SELECT g.name FROM groups g JOIN group_members gm ON g.id=gm.group_id WHERE gm.user_id=$id LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .profile-layout { display: grid; grid-template-columns: 260px 1fr; gap: 20px; align-items: start; }

    /* SIDEBAR */
    .profile-sidebar { display: flex; flex-direction: column; gap: 14px; }
    .avatar-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; text-align: center; box-shadow: var(--shadow); }
    .profile-photo-wrap { position: relative; width: 80px; margin: 0 auto 14px; }
    .profile-photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--indigo-light); }
    .profile-name { font-size: 17px; font-weight: 600; }
    .profile-branch { font-size: 13px; color: var(--muted); margin-top: 3px; margin-bottom: 14px; }
    .profile-stats-mini { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .ps-mini { background: var(--bg); border-radius: 8px; padding: 10px; text-align: center; }
    .ps-mini-num { font-size: 18px; font-weight: 600; color: var(--indigo); font-family: 'Sora', sans-serif; }
    .ps-mini-lbl { font-size: 11px; color: var(--muted); margin-top: 2px; }

    .vis-toggle { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; box-shadow: var(--shadow); }
    .vis-label { font-size: 13px; font-weight: 500; }
    .vis-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
    .toggle-switch { position: relative; width: 40px; height: 22px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; background: #d1d5db; border-radius: 999px; cursor: pointer; transition: background 0.2s; }
    .toggle-slider::before { content:''; position:absolute; width:16px; height:16px; left:3px; top:3px; background:white; border-radius:50%; transition: transform 0.2s; }
    .toggle-switch input:checked + .toggle-slider { background: var(--indigo); }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }

    /* MAIN FORM */
    .profile-form-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; box-shadow: var(--shadow); }
    .form-section-title { font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid var(--border); margin-top: 20px; }
    .form-section-title:first-child { margin-top: 0; }

    .photo-upload-row { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .photo-preview { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid var(--indigo-light); }
    .upload-info { font-size: 12px; color: var(--muted); margin-top: 4px; }

    .save-row { display: flex; align-items: center; gap: 14px; margin-top: 6px; }
    .save-success { font-size: 13px; color: var(--success); display: flex; align-items: center; gap: 5px; }

    .groups-list { list-style: none; }
    .groups-list li { padding: 8px 0; border-bottom: 1px solid var(--border); font-size: 13.5px; display: flex; align-items: center; gap: 8px; }
    .groups-list li:last-child { border-bottom: none; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrap">

  <div class="profile-layout">

    <!-- SIDEBAR -->
    <div class="profile-sidebar">
      <div class="avatar-card">
        <?php if(!empty($user['photo'])): ?>
          <img src="<?php echo htmlspecialchars($user['photo']); ?>" class="profile-photo" style="display:block;margin:0 auto 14px">
        <?php else: ?>
          <div class="avatar avatar-xl avatar-indigo" style="margin:0 auto 14px"><?php echo $initials; ?></div>
        <?php endif; ?>

        <div class="profile-name"><?php echo htmlspecialchars($user['name']); ?></div>
        <div class="profile-branch"><?php echo htmlspecialchars($user['branch'] ?? ''); ?> · <?php echo htmlspecialchars($user['year'] ?? ''); ?></div>

        <div class="profile-stats-mini">
          <div class="ps-mini"><div class="ps-mini-num"><?php echo $groups_count; ?></div><div class="ps-mini-lbl">Groups</div></div>
          <div class="ps-mini"><div class="ps-mini-num">1</div><div class="ps-mini-lbl">Profile</div></div>
        </div>
      </div>

      <!-- VISIBILITY TOGGLE -->
      <div class="vis-toggle">
        <div>
          <div class="vis-label">Discoverable</div>
          <div class="vis-sub">Appear in discover page</div>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" id="visToggle" <?php echo ($user['visibility'] ?? 1) ? 'checked' : ''; ?> onchange="updateVisibility(this.checked)">
          <span class="toggle-slider"></span>
        </label>
      </div>

      <!-- MY GROUPS -->
      <?php if($groups_count > 0): ?>
      <div class="card">
        <div class="section-title">My Groups</div>
        <ul class="groups-list">
          <?php while($g = $my_groups->fetch_assoc()): ?>
            <li>👥 <?php echo htmlspecialchars($g['name']); ?></li>
          <?php endwhile; ?>
        </ul>
        <a href="groups.php" style="font-size:12px;color:var(--indigo);display:block;margin-top:10px">View all groups →</a>
      </div>
      <?php endif; ?>
    </div>

    <!-- MAIN FORM -->
    <div class="profile-form-card">

      <?php if(isset($_GET['success'])): ?>
        <div class="msg-success">✓ Profile updated successfully!</div>
      <?php endif; ?>

      <form method="POST" action="update_profile.php" enctype="multipart/form-data">

        <div class="form-section-title">Basic Info</div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input class="form-input" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-input" name="email" type="email" value="<?php echo htmlspecialchars($user['email']); ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Branch</label>
            <input class="form-input" name="branch" value="<?php echo htmlspecialchars($user['branch'] ?? ''); ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Year</label>
            <select class="form-select" name="year">
              <?php foreach(['First Year','Second Year','Third Year','Final Year'] as $y): ?>
                <option <?php echo ($user['year'] ?? '') === $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Gender</label>
            <select class="form-select" name="gender">
              <?php foreach(['Male','Female','Prefer not to say'] as $g): ?>
                <option <?php echo ($user['gender'] ?? '') === $g ? 'selected' : ''; ?>><?php echo $g; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-section-title">Profile Photo</div>
        <div class="photo-upload-row">
          <?php if(!empty($user['photo'])): ?>
            <img src="<?php echo htmlspecialchars($user['photo']); ?>" class="photo-preview" alt="Current photo">
          <?php else: ?>
            <div class="avatar avatar-md avatar-indigo"><?php echo $initials; ?></div>
          <?php endif; ?>
          <div>
            <input type="file" name="photo" accept="image/*" class="form-input" style="padding:6px">
            <div class="upload-info">JPG, PNG · Max 5MB</div>
          </div>
        </div>

        <div class="form-section-title">About You</div>
        <div class="form-group">
          <label class="form-label">Bio</label>
          <textarea class="form-textarea" name="bio" rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Skills</label>
            <input class="form-input" name="skills" placeholder="e.g. Python, React, Figma" value="<?php echo htmlspecialchars($user['skills'] ?? ''); ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Interests</label>
            <input class="form-input" name="interests" placeholder="e.g. Hackathons, Music" value="<?php echo htmlspecialchars($user['interests'] ?? ''); ?>">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Looking For</label>
          <select class="form-select" name="looking_for">
            <?php foreach(['Hackathon Team','Travel Buddy','Hostel Friends','Roommate','Networking'] as $lf): ?>
              <option <?php echo ($user['looking_for'] ?? '') === $lf ? 'selected' : ''; ?>><?php echo $lf; ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-section-title">Links</div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">LinkedIn</label>
            <input class="form-input" name="linkedin" placeholder="https://linkedin.com/in/..." value="<?php echo htmlspecialchars($user['linkedin'] ?? ''); ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Portfolio / GitHub</label>
            <input class="form-input" name="portfolio" placeholder="https://yoursite.com" value="<?php echo htmlspecialchars($user['portfolio'] ?? ''); ?>">
          </div>
        </div>

        <div class="save-row">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>

      </form>
    </div>

  </div>
</div>

<script>
function updateVisibility(checked) {
  fetch('update_visibility.php?v=' + (checked ? 1 : 0));
}
</script>
</body>
</html>
