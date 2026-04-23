<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$group_id = intval($_GET['id'] ?? 0);
if (!$group_id) { header("Location: groups.php"); exit(); }

$group = $conn->query("SELECT * FROM `groups` WHERE id=$group_id")->fetch_assoc();
if (!$group) { header("Location: groups.php"); exit(); }

$members = $conn->query("SELECT users.* FROM group_members JOIN users ON group_members.user_id = users.id WHERE group_members.group_id=$group_id ORDER BY group_members.joined_at DESC");
$member_count = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE group_id=$group_id")->fetch_assoc()['t'];
$user_joined = $conn->query("SELECT id FROM group_members WHERE user_id={$_SESSION['user']['id']} AND group_id=$group_id")->num_rows > 0;

$cat_icons = ['Coding'=>'💻','Interest'=>'⭐','Hostel'=>'🏠','Travel'=>'🚗','Events'=>'🎉','Development'=>'🛠️'];
$icon = $cat_icons[$group['category']] ?? '👥';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($group['name']); ?> | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .back-link { display: inline-flex; align-items: center; gap: 5px; font-size: 13px; color: var(--muted); margin-bottom: 20px; transition: color 0.15s; }
    .back-link:hover { color: var(--indigo); }

    .group-hero { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; margin-bottom: 24px; box-shadow: var(--shadow); display: flex; gap: 22px; align-items: flex-start; }
    .hero-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--indigo-light); display: flex; align-items: center; justify-content: center; font-size: 28px; flex-shrink: 0; }
    .hero-info { flex: 1; }
    .hero-info h1 { font-size: 22px; font-weight: 600; margin-bottom: 6px; }
    .hero-info p { font-size: 14px; color: var(--muted); line-height: 1.6; margin-bottom: 12px; }
    .hero-meta { display: flex; gap: 16px; font-size: 13px; color: var(--muted); flex-wrap: wrap; }
    .hero-meta span { display: flex; align-items: center; gap: 4px; }
    .hero-actions { display: flex; gap: 10px; align-items: center; flex-shrink: 0; }

    .members-section h2 { font-size: 18px; font-weight: 600; margin-bottom: 16px; }
    .members-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; }

    .member-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px; text-align: center; box-shadow: var(--shadow); transition: transform 0.2s; }
    .member-card:hover { transform: translateY(-3px); }
    .member-avatar-wrap { margin: 0 auto 10px; }
    .member-photo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block; margin: 0 auto 10px; }
    .member-name { font-size: 13.5px; font-weight: 600; }
    .member-branch { font-size: 11.5px; color: var(--muted); margin-top: 3px; }
    .member-skills { margin-top: 8px; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrap">

  <a href="groups.php" class="back-link">← Back to Groups</a>

  <!-- GROUP HERO -->
  <div class="group-hero">
    <div class="hero-icon"><?php echo $icon; ?></div>
    <div class="hero-info">
      <h1><?php echo htmlspecialchars($group['name']); ?></h1>
      <p><?php echo htmlspecialchars($group['description'] ?? ''); ?></p>
      <div class="hero-meta">
        <span>👥 <?php echo $member_count; ?> members</span>
        <span>📁 <?php echo htmlspecialchars($group['category'] ?? ''); ?></span>
        <span>🔢 Max: <?php echo ($group['max_members'] && $group['max_members'] > 0) ? $group['max_members'] : 'Unlimited'; ?></span>
        <?php if($group['purpose']): ?><span>🎯 <?php echo htmlspecialchars($group['purpose']); ?></span><?php endif; ?>
      </div>
    </div>
    <div class="hero-actions">
      <?php if($user_joined): ?>
        <span class="joined-pill">✓ Joined</span>
      <?php else: ?>
        <a href="join_group.php?id=<?php echo $group_id; ?>"><button class="btn btn-primary">Join Group</button></a>
      <?php endif; ?>
    </div>
  </div>

  <!-- MEMBERS -->
  <div class="members-section">
    <h2>Members (<?php echo $member_count; ?>)</h2>
    <?php if($member_count === 0): ?>
      <p style="color:var(--muted);font-size:13.5px">No members yet. Be the first to join!</p>
    <?php else: ?>
    <div class="members-grid">
      <?php
      $colors = ['avatar-indigo','avatar-teal','avatar-rose','avatar-amber'];
      $ci = 0;
      while($m = $members->fetch_assoc()):
        $initials = strtoupper(substr($m['name'],0,1));
        $color = $colors[$ci % count($colors)]; $ci++;
      ?>
      <div class="member-card">
        <?php if(!empty($m['photo'])): ?>
          <img src="<?php echo htmlspecialchars($m['photo']); ?>" class="member-photo" alt="<?php echo htmlspecialchars($m['name']); ?>">
        <?php else: ?>
          <div class="avatar avatar-lg <?php echo $color; ?> member-avatar-wrap"><?php echo $initials; ?></div>
        <?php endif; ?>
        <div class="member-name"><?php echo htmlspecialchars($m['name']); ?></div>
        <div class="member-branch"><?php echo htmlspecialchars($m['branch'] ?? ''); ?> · <?php echo htmlspecialchars($m['year'] ?? ''); ?></div>
        <?php if(!empty($m['skills'])): ?>
          <div class="member-skills">
            <div class="tag-row" style="justify-content:center">
              <?php foreach(array_slice(explode(',', $m['skills']), 0, 2) as $sk): ?>
                <span class="tag"><?php echo htmlspecialchars(trim($sk)); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
