<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
include 'db.php';

$user_id = $_SESSION['user']['id'];
$search = $conn->real_escape_string($_GET['search'] ?? '');
$category = $conn->real_escape_string($_GET['category'] ?? '');

$query = "SELECT * FROM `groups` WHERE 1";
if ($search) $query .= " AND name LIKE '%$search%'";
if ($category) $query .= " AND category='$category'";
$query .= " ORDER BY id DESC";
$result = $conn->query($query);

// Get user's joined groups for badge
$joined_ids = [];
$jq = $conn->query("SELECT group_id FROM group_members WHERE user_id=$user_id");
while($r = $jq->fetch_assoc()) $joined_ids[] = $r['group_id'];

$error = $_GET['error'] ?? '';

// emojis removed
$cat_icons = ['Coding'=>'','Interest'=>'','Hostel'=>'','Travel'=>'','Events'=>'','Development'=>''];
$cat_colors = ['Coding'=>'badge-indigo','Interest'=>'badge-indigo','Hostel'=>'badge-amber','Travel'=>'badge-blue','Events'=>'badge-pink','Development'=>'badge-green'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Groups | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .page-header { margin-bottom: 22px; }
    .page-header h1 { font-size: 24px; font-weight: 600; }
    .page-header p { font-size: 13.5px; color: var(--muted); margin-top: 3px; }

    .filter-row { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; flex-wrap: wrap; }
    .filter-row form { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; width: 100%; }

    .groups-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }

    .group-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); transition: transform 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; }
    .group-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

    .gc-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .gc-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--indigo-light); display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .gc-title { font-size: 15px; font-weight: 600; margin-bottom: 6px; }
    .gc-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 14px; flex: 1; }
    .gc-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
    .gc-meta { display: flex; flex-direction: column; gap: 3px; }
    .gc-members { font-size: 12px; color: var(--muted); }
    .gc-limit { font-size: 11px; color: var(--muted); }

    .progress-bar-wrap { margin-bottom: 14px; }
    .progress-label { display: flex; justify-content: space-between; font-size: 11px; color: var(--muted); margin-bottom: 4px; }
    .progress-bar { height: 4px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
    .progress-fill { height: 100%; background: var(--indigo); border-radius: 999px; transition: width 0.3s; }

    .select-filter { padding: 7px 12px; border-radius: var(--radius-pill); border: 1px solid var(--border-md); background: white; font-size: 13px; font-family: 'DM Sans', sans-serif; color: var(--muted); cursor: pointer; }
    .select-filter:focus { outline: none; border-color: var(--indigo); }

    .apply-btn { padding: 7px 18px; border-radius: var(--radius-pill); background: var(--indigo); color: white; border: none; font-size: 13px; font-family: 'DM Sans', sans-serif; cursor: pointer; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrap">

  <div class="page-header">
    <h1>Groups &amp; Communities</h1>
    <p>Join communities, find travel buddies, or meet hostel mates</p>
  </div>

  <?php if($error === 'full'): ?>
    <div class="msg-error">This group is full and cannot accept new members.</div>
  <?php endif; ?>

  <!-- FILTERS -->
  <div class="filter-row">
    <form method="GET">
      <input type="text" name="search" class="search-input" placeholder="Search groups..." value="<?php echo htmlspecialchars($search); ?>">
      <select name="category" class="select-filter">
        <option value="">All Categories</option>
        <?php foreach(['Coding','Interest','Hostel','Travel','Events','Development'] as $cat): ?>
          <option value="<?php echo $cat; ?>" <?php echo $category===$cat?'selected':''; ?>><?php echo $cat; ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="apply-btn">Apply</button>
      <?php if($search || $category): ?>
        <a href="groups.php"><button type="button" class="btn btn-outline btn-sm">Clear</button></a>
      <?php endif; ?>
    </form>
  </div>

  <!-- GROUPS -->
  <div class="groups-grid">
    <?php
    $found = false;
    while($row = $result->fetch_assoc()):
      $found = true;
      $gid = $row['id'];
      $member_count = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE group_id=$gid")->fetch_assoc()['t'];
      $max = $row['max_members'];
      $is_joined = in_array($gid, $joined_ids);
      $icon = $cat_icons[$row['category']] ?? '';
      $badgecls = $cat_colors[$row['category']] ?? 'badge-gray';
      $percent = ($max && $max > 0) ? min(100, round(($member_count / $max) * 100)) : 0;
      $top_members = $conn->query("SELECT name FROM users u JOIN group_members gm ON u.id=gm.user_id WHERE gm.group_id=$gid LIMIT 3");
    ?>
    <div class="group-card">
      <div class="gc-top">
        <div class="gc-icon"><?php echo $icon; ?></div>
        <span class="badge <?php echo $badgecls; ?>"><?php echo htmlspecialchars($row['category'] ?? ''); ?></span>
      </div>

      <div class="gc-title"><?php echo htmlspecialchars($row['name']); ?></div>
      <div class="gc-desc"><?php echo htmlspecialchars($row['description'] ?? ''); ?></div>

      <?php if($max && $max > 0): ?>
      <div class="progress-bar-wrap">
        <div class="progress-label">
          <span><?php echo $member_count; ?> / <?php echo $max; ?> members</span>
          <span><?php echo $percent; ?>%</span>
        </div>
        <div class="progress-bar"><div class="progress-fill" style="width:<?php echo $percent; ?>%"></div></div>
      </div>
      <?php endif; ?>

      <div class="gc-footer">
        <div class="gc-meta">
          <div class="member-stack">
            <?php while($m = $top_members->fetch_assoc()): ?>
              <div class="member-dot"><?php echo strtoupper(substr($m['name'],0,1)); ?></div>
            <?php endwhile; ?>
          </div>
          <div class="gc-members"><?php echo $member_count; ?> members<?php echo (!$max || $max == 0) ? ' · Unlimited' : ''; ?></div>
        </div>

        <?php if($is_joined): ?>
          <a href="group_details.php?id=<?php echo $gid; ?>"><span class="joined-pill">Joined</span></a>
        <?php else: ?>
          <a href="join_group.php?id=<?php echo $gid; ?>"><button class="join-pill">Join</button></a>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile; ?>

    <?php if(!$found): ?>
      <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:var(--muted)">
        <p style="font-size:16px;margin-bottom:6px">No groups found</p>
        <p style="font-size:13px">Try a different search or <a href="groups.php" style="color:var(--indigo)">clear filters</a></p>
      </div>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
