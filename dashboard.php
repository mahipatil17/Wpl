<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
include 'db.php';

$user_id = $_SESSION['user']['id'];

// Real stats from DB
$groups_joined = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE user_id=$user_id")->fetch_assoc()['t'];
$my_groups = $conn->query("SELECT g.* FROM groups g JOIN group_members gm ON g.id=gm.group_id WHERE gm.user_id=$user_id ORDER BY gm.joined_at DESC LIMIT 3");
$suggested = $conn->query("SELECT * FROM groups WHERE id NOT IN (SELECT group_id FROM group_members WHERE user_id=$user_id) ORDER BY id DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .welcome-bar { margin-bottom: 24px; }
    .welcome-bar h1 { font-size: 24px; font-weight: 600; }
    .welcome-bar p { font-size: 13.5px; color: var(--muted); margin-top: 3px; }

    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }

    .card-title { font-size: 14px; font-weight: 600; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
    .card-title a { font-size: 12px; color: var(--indigo); font-weight: 400; }

    .group-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); }
    .group-row:last-child { border-bottom: none; }
    .group-row-info h4 { font-size: 13.5px; font-weight: 500; }
    .group-row-info p { font-size: 12px; color: var(--muted); margin-top: 2px; }

    /* EVENTS BELT */
    .belt-section { margin-bottom: 24px; }
    .belt-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .belt-header h2 { font-size: 16px; font-weight: 600; }
    .belt-hint { font-size: 12px; color: var(--muted); }

    .belt-outer { position: relative; overflow: hidden; }
    .belt-fade-l { position: absolute; top:0; left:0; bottom:0; width:40px; background: linear-gradient(to right, var(--bg), transparent); z-index:2; pointer-events:none; }
    .belt-fade-r { position: absolute; top:0; right:0; bottom:0; width:40px; background: linear-gradient(to left, var(--bg), transparent); z-index:2; pointer-events:none; }

    .belt-track { display: flex; gap: 14px; width: max-content; }

    .ev-card {
      width: 280px; min-width: 280px;
      background: var(--surface);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: transform 0.2s;
    }
    .ev-card:hover { transform: translateY(-4px); }

    .ev-poster { height: 140px; position: relative; overflow: hidden; }
    .ev-poster img { width:100%; height:100%; object-fit:cover; display:block; }
    .ev-poster-fallback { width:100%; height:100%; background: linear-gradient(135deg, var(--indigo-light), #dbeafe); display:flex; align-items:center; justify-content:center; font-size:38px; }

    .ev-badge-wrap { position:absolute; top:8px; left:8px; }

    .ev-body { padding: 14px 16px; }
    .ev-date { font-size: 11px; color: var(--muted); margin-bottom: 5px; }
    .ev-title { font-size: 13.5px; font-weight: 600; line-height: 1.4; margin-bottom: 6px; }
    .ev-desc { font-size: 12px; color: var(--muted); line-height: 1.5; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .ev-link { font-size: 12px; }

    /* EVENTS GRID */
    .events-section { margin-bottom: 24px; }
    .events-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .event-tile { border-radius: var(--radius); padding: 20px; transition: transform 0.2s; cursor: pointer; }
    .event-tile:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .event-tile-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.5); display:flex; align-items:center; justify-content:center; font-size:18px; margin-bottom:10px; }
    .event-tile h3 { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
    .event-tile p { font-size: 12px; opacity: 0.75; margin-bottom: 6px; }
    .event-tile-tag { font-size: 11px; font-weight: 500; opacity: 0.8; }
    .tile-1 { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
    .tile-2 { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }
    .tile-3 { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #9d174d; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrap">

  <div class="welcome-bar">
    <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h1>
    <p>Here's what's happening in your FY community</p>
  </div>

  <!-- STATS -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-num"><?php echo $groups_joined; ?></div>
      <div class="stat-lbl">Groups Joined</div>
    </div>
    <div class="stat-card">
      <div class="stat-num">18</div>
      <div class="stat-lbl">Connections Made</div>
    </div>
    <div class="stat-card">
      <div class="stat-num">6</div>
      <div class="stat-lbl">Upcoming Events</div>
    </div>
    <div class="stat-card">
      <div class="stat-num">24/7</div>
      <div class="stat-lbl">Platform Access</div>
    </div>
  </div>

  <!-- MY GROUPS + SUGGESTED -->
  <div class="two-col">
    <div class="card">
      <div class="card-title">
        My Groups
        <a href="groups.php">View All →</a>
      </div>
      <?php if ($my_groups->num_rows === 0): ?>
        <p style="font-size:13px;color:var(--muted)">You haven't joined any groups yet. <a href="groups.php" style="color:var(--indigo)">Explore groups →</a></p>
      <?php else: ?>
        <?php while($g = $my_groups->fetch_assoc()): ?>
          <?php $cnt = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE group_id={$g['id']}")->fetch_assoc()['t']; ?>
          <div class="group-row">
            <div class="group-row-info">
              <h4><?php echo htmlspecialchars($g['name']); ?></h4>
              <p><?php echo $cnt; ?> members · <?php echo htmlspecialchars($g['category'] ?? ''); ?></p>
            </div>
            <span class="joined-pill">Joined</span>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <div class="card">
      <div class="card-title">Suggested Groups</div>
      <?php if ($suggested->num_rows === 0): ?>
        <p style="font-size:13px;color:var(--muted)">You've joined all available groups!</p>
      <?php else: ?>
        <?php while($g = $suggested->fetch_assoc()): ?>
          <?php $cnt = $conn->query("SELECT COUNT(*) as t FROM group_members WHERE group_id={$g['id']}")->fetch_assoc()['t']; ?>
          <div class="group-row">
            <div class="group-row-info">
              <h4><?php echo htmlspecialchars($g['name']); ?></h4>
              <p><?php echo $cnt; ?> members · <?php echo htmlspecialchars($g['category'] ?? ''); ?></p>
            </div>
            <a href="join_group.php?id=<?php echo $g['id']; ?>"><button class="join-pill">Join</button></a>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- EVENTS BELT -->
  <div class="belt-section">
    <div class="belt-header">
      <h2>🗓️ Upcoming Events</h2>
      <span class="belt-hint">Hover to pause</span>
    </div>
    <div class="belt-outer" id="beltOuter">
      <div class="belt-fade-l"></div>
      <div class="belt-fade-r"></div>
      <div class="belt-track" id="beltTrack">

        <div class="ev-card">
          <div class="ev-poster"><img src="images/hackx.png" alt="HackX" onerror="this.parentNode.innerHTML='<div class=ev-poster-fallback>🏆</div>'">
          <div class="ev-badge-wrap"><span class="badge badge-indigo">Hackathon</span></div></div>
          <div class="ev-body">
            <div class="ev-date">📅 Apr 11–12, 2026</div>
            <div class="ev-title">HackX – 24hr Coding Challenge</div>
            <div class="ev-desc">CodeCell presents KJSSE HACK X. 24 hours, ₹3 Lakhs in prizes.</div>
            <a href="https://hack.kjsse.com" target="_blank"><button class="join-pill ev-link">Register →</button></a>
          </div>
        </div>

        <div class="ev-card">
          <div class="ev-poster"><img src="images/idea.png" alt="IDEA" onerror="this.parentNode.innerHTML='<div class=ev-poster-fallback>💡</div>'">
          <div class="ev-badge-wrap"><span class="badge badge-blue">Hackathon</span></div></div>
          <div class="ev-body">
            <div class="ev-date">📅 Apr 2, 2026</div>
            <div class="ev-title">IDEA 2.O – PSB Hackathon Series</div>
            <div class="ev-desc">A premier 24-hour hackathon focused on financial technology.</div>
            <a href="https://www.ideahackathon.com/" target="_blank"><button class="join-pill ev-link">Know More →</button></a>
          </div>
        </div>

        <div class="ev-card">
          <div class="ev-poster"><img src="images/finovatex.png" alt="FinovateX" onerror="this.parentNode.innerHTML='<div class=ev-poster-fallback>📈</div>'">
          <div class="ev-badge-wrap"><span class="badge badge-green">Challenge</span></div></div>
          <div class="ev-body">
            <div class="ev-date">📅 Apr 18, 2026</div>
            <div class="ev-title">FinovateX – Fintech Innovation</div>
            <div class="ev-desc">Finance & Tech Club presents an exciting fintech challenge.</div>
            <a href="https://unstop.com" target="_blank"><button class="join-pill ev-link">Register →</button></a>
          </div>
        </div>

        <div class="ev-card">
          <div class="ev-poster"><img src="images/techmeet.png" alt="TechMeet" onerror="this.parentNode.innerHTML='<div class=ev-poster-fallback>🎤</div>'">
          <div class="ev-badge-wrap"><span class="badge badge-amber">Meetup</span></div></div>
          <div class="ev-body">
            <div class="ev-date">📅 May 5, 2026</div>
            <div class="ev-title">Cracking BIG TECH</div>
            <div class="ev-desc">CodeCell Presents: Your Roadmap to Top Tech MNCs | Alumni Live.</div>
            <a href="#"><button class="join-pill ev-link">Enroll Now →</button></a>
          </div>
        </div>

        <div class="ev-card">
          <div class="ev-poster"><img src="images/bloombox.png" alt="CaseQuest" onerror="this.parentNode.innerHTML='<div class=ev-poster-fallback>⚔️</div>'">
          <div class="ev-badge-wrap"><span class="badge badge-pink">Competition</span></div></div>
          <div class="ev-body">
            <div class="ev-date">📅 Apr 3, 2026</div>
            <div class="ev-title">Case Quest – Strategy Arena</div>
            <div class="ev-desc">Where thinkers turn into strategists. 11am–5pm, KJSCE.</div>
            <a href="https://unstop.com" target="_blank"><button class="join-pill ev-link">Register →</button></a>
          </div>
        </div>

        <div class="ev-card">
          <div class="ev-poster"><img src="images/vishwanova.png" alt="VishwaNova" onerror="this.parentNode.innerHTML='<div class=ev-poster-fallback>🌐</div>'">
          <div class="ev-badge-wrap"><span class="badge badge-gray">Design</span></div></div>
          <div class="ev-body">
            <div class="ev-date">📅 Apr 16, 2026</div>
            <div class="ev-title">VishwaNova 2026 – National Level</div>
            <div class="ev-desc">Ideate · Innovate · Elevate. Prize Pool ₹2,50,000+.</div>
            <a href="https://unstop.com" target="_blank"><button class="join-pill ev-link">Register →</button></a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- BROWSE ALL EVENTS -->
  <div class="events-section">
    <h2 style="font-size:16px;font-weight:600;margin-bottom:6px">Browse All Events</h2>
    <p style="font-size:13px;color:var(--muted);margin-bottom:14px">Discover, participate &amp; make your college life unforgettable</p>
    <div class="events-grid">
      <div class="event-tile tile-1">
        <h3>Hackathon</h3>
        <p>Apr 11 · 24-hr challenge</p>
        <div class="event-tile-tag">Code. Compete. Conquer.</div>
      </div>
      <div class="event-tile tile-2">
        <h3>Tech Meetup</h3>
        <p>May 5 · Networking &amp; talks</p>
        <div class="event-tile-tag">Meet minds that inspire.</div>
      </div>
      <div class="event-tile tile-3">
        <h3>Cultural Fest</h3>
        <p>Apr 18 · Competitions</p>
        <div class="event-tile-tag">Unleash your creative side.</div>
      </div>
    </div>
  </div>

</div>

<script>
(function() {
  var track = document.getElementById('beltTrack');
  var outer = document.getElementById('beltOuter');
  if (!track || !outer) return;
  var orig = Array.from(track.children);
  orig.forEach(function(c) { track.appendChild(c.cloneNode(true)); });
  var offset = 0, paused = false, SPEED = 0.5;
  function sw() { return orig.reduce(function(s,c){ return s + c.offsetWidth + 14; }, 0); }
  function tick() {
    if (!paused) { offset += SPEED; var w = sw(); if (w > 0 && offset >= w) offset -= w; track.style.transform = 'translateX(-' + offset + 'px)'; }
    requestAnimationFrame(tick);
  }
  outer.addEventListener('mouseenter', function(){ paused = true; });
  outer.addEventListener('mouseleave', function(){ paused = false; });
  document.addEventListener('visibilitychange', function(){ paused = document.hidden; });
  requestAnimationFrame(tick);
})();
</script>

</body>
</html>
