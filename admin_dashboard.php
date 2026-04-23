<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }
include 'db.php';

$userCount = $conn->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'];
$groupCount = $conn->query("SELECT COUNT(*) as t FROM `groups`")->fetch_assoc()['t'];
$memberCount = $conn->query("SELECT COUNT(*) as t FROM group_members")->fetch_assoc()['t'];
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$groups = $conn->query("SELECT * FROM `groups` ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .admin-nav { display: flex; align-items: center; justify-content: space-between; height: 58px; padding: 0 32px; background: rgba(255,255,255,0.88); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border); position: sticky; top:0; z-index:100; }
    .admin-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; background: #fee2e2; color: #dc2626; font-size: 11.5px; font-weight: 500; }
    .badge-dot-red { width: 5px; height: 5px; border-radius: 50%; background: #dc2626; }

    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .full-col { margin-top: 20px; }

    .table-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
    .table-head { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); background: #fafafa; }
    .table-head h3 { font-size: 14px; font-weight: 600; }

    .user-row { display: flex; align-items: center; gap: 12px; padding: 12px 20px; border-bottom: 1px solid var(--border); font-size: 13px; }
    .user-row:last-child { border-bottom: none; }
    .user-row:hover { background: #fafafa; }
    .user-name { flex: 1; font-weight: 500; }
    .user-branch { flex: 1; color: var(--muted); }
    .user-email { flex: 1.5; color: var(--muted); font-size: 12px; }

    .group-row-admin { display: flex; align-items: flex-start; gap: 14px; padding: 14px 20px; border-bottom: 1px solid var(--border); }
    .group-row-admin:last-child { border-bottom: none; }
    .group-row-admin:hover { background: #fafafa; }
    .gra-icon { width: 36px; height: 36px; border-radius: 9px; background: var(--indigo-light); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .gra-info { flex: 1; }
    .gra-name { font-size: 13.5px; font-weight: 500; }
    .gra-desc { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .gra-meta { font-size: 11.5px; color: var(--muted); margin-top: 4px; }

    .create-group-form { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); }
    .create-group-form h3 { font-size: 16px; font-weight: 600; margin-bottom: 18px; }
  </style>
</head>
<body style="background:var(--bg)">

<nav class="admin-nav">
  <div class="nav-left">
    <div class="logo-box">🎓</div>
    <span class="logo-text">FY Connect</span>
    <div class="admin-badge"><div class="badge-dot-red"></div> Admin</div>
  </div>
  <div class="nav-center">
    <a href="#create" class="nav-link">Create Group</a>
  </div>
  <a href="admin_logout.php"><button class="btn btn-outline btn-sm">Logout</button></a>
</nav>

<div class="page-wrap">

  <!-- STATS -->
  <div style="margin-bottom:20px">
    <h1 style="font-size:22px;font-weight:600;margin-bottom:4px">Admin Dashboard</h1>
    <p style="font-size:13px;color:var(--muted)">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['admin']); ?></strong></p>
  </div>

  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card"><div class="stat-icon"></div><div class="stat-num"><?php echo $userCount; ?></div><div class="stat-lbl">Total Students</div></div>
    <div class="stat-card"><div class="stat-icon"></div><div class="stat-num"><?php echo $groupCount; ?></div><div class="stat-lbl">Total Groups</div></div>
    <div class="stat-card"><div class="stat-icon"></div><div class="stat-num"><?php echo $memberCount; ?></div><div class="stat-lbl">Group Memberships</div></div>
  </div>

  <!-- STUDENTS TABLE -->
  <div class="full-col" id="users">
    <div class="table-card">
      <div class="table-head">
        <h3>All Students (<?php echo $userCount; ?>)</h3>
      </div>
      <?php while($u = $users->fetch_assoc()):
        $ug = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE user_id={$u['id']}")->fetch_assoc()['t'];
        $initials = strtoupper(substr($u['name'],0,1));
        $colors = ['avatar-indigo','avatar-teal','avatar-rose','avatar-amber'];
        static $ci2 = 0;
        $col = $colors[$ci2++ % count($colors)];
      ?>
      <div class="user-row">
        <?php if(!empty($u['photo'])): ?>
          <img src="<?php echo htmlspecialchars($u['photo']); ?>" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
        <?php else: ?>
          <div class="avatar avatar-sm <?php echo $col; ?>"><?php echo $initials; ?></div>
        <?php endif; ?>
        <div class="user-name"><?php echo htmlspecialchars($u['name']); ?></div>
        <div class="user-email"><?php echo htmlspecialchars($u['email']); ?></div>
        <div class="user-branch"><?php echo htmlspecialchars($u['branch'] ?? ''); ?></div>
        <span class="badge badge-gray"><?php echo htmlspecialchars($u['year'] ?? ''); ?></span>
        <span class="badge badge-indigo"><?php echo $ug; ?> groups</span>
        <a href="delete_user.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Remove <?php echo htmlspecialchars($u['name']); ?>? This cannot be undone.')"><button class="btn btn-danger btn-sm">Remove</button></a>
      </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- GROUPS TABLE + CREATE -->
  <div class="two-col" style="margin-top:20px" id="groups">
    <div class="table-card">
      <div class="table-head">
        <h3>All Groups (<?php echo $groupCount; ?>)</h3>
      </div>
      <?php
      $cat_icons = ['Coding'=>'','Interest'=>'','Hostel'=>'','Travel'=>'','Events'=>'','Development'=>''];
      while($g = $groups->fetch_assoc()):
        $mc = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE group_id={$g['id']}")->fetch_assoc()['t'];
        $icon = $cat_icons[$g['category']] ?? '👥';
      ?>
      <div class="group-row-admin">
        <div class="gra-icon"><?php echo $icon; ?></div>
        <div class="gra-info">
          <div class="gra-name"><?php echo htmlspecialchars($g['name']); ?></div>
          <div class="gra-desc"><?php echo htmlspecialchars(substr($g['description'] ?? '', 0, 60)); ?>...</div>
          <div class="gra-meta"><?php echo $mc; ?> members · <?php echo htmlspecialchars($g['category'] ?? ''); ?> · Max: <?php echo ($g['max_members'] && $g['max_members']>0) ? $g['max_members'] : 'Unlimited'; ?></div>
        </div>
        <span class="badge badge-<?php echo $mc>0?'green':'gray'; ?>"><?php echo $mc; ?></span>
      </div>
      <?php endwhile; ?>
    </div>

    <!-- CREATE GROUP -->
    <div class="create-group-form" id="create">
      <h3> Create New Group</h3>
      <?php if(isset($_GET['created'])): ?>
        <div class="msg-success" style="margin-bottom:14px">✓ Group created successfully!</div>
      <?php endif; ?>
      <form method="POST" action="create_group_process.php">
        <div class="form-group">
          <label class="form-label">Group Name</label>
          <input class="form-input" name="name" placeholder="e.g. Photography Club" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-textarea" name="description" placeholder="Describe what this group is about..."></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Category</label>
            <select class="form-select" name="category">
              <option>Coding</option><option>Interest</option><option>Hostel</option><option>Travel</option><option>Events</option><option>Development</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Max Members</label>
            <select class="form-select" name="max_members">
              <option value="">Unlimited</option>
              <option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="50">50</option><option value="100">100</option>
            </select>
          </div>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Create Group</button>
      </form>
    </div>
  </div>

</div>
</body>
</html>
