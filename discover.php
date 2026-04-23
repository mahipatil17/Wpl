<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$result = $conn->query("SELECT * FROM users WHERE visibility=1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discover | FY Connect</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .discover-header { margin-bottom: 22px; }
    .discover-header h1 { font-size: 24px; font-weight: 600; }
    .discover-header p { font-size: 13.5px; color: var(--muted); margin-top: 3px; }

    .filter-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; flex-wrap: wrap; }

    .students-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 16px; }

    /* FLIP CARD */
    .student-card { perspective: 900px; height: 280px; cursor: pointer; }
    .card-inner {
      position: relative; width: 100%; height: 100%;
      transition: transform 0.55s cubic-bezier(0.4,0,0.2,1);
      transform-style: preserve-3d;
    }
    .student-card.flipped .card-inner { transform: rotateY(180deg); }

    .card-front, .card-back {
      position: absolute; width: 100%; height: 100%;
      backface-visibility: hidden; border-radius: var(--radius);
      border: 1px solid var(--border); box-shadow: var(--shadow);
      overflow: hidden;
    }

    .card-front { background: var(--surface); }
    .card-back { background: var(--indigo); transform: rotateY(180deg); padding: 18px; display: flex; flex-direction: column; }

    .card-photo {
      height: 120px; background: linear-gradient(135deg, var(--indigo-light), #dbeafe);
      display: flex; align-items: center; justify-content: center;
      font-size: 42px; overflow: hidden;
    }
    .card-photo img { width:100%; height:100%; object-fit:cover; display:block; }

    .card-info { padding: 14px 16px; }
    .card-name { font-size: 14.5px; font-weight: 600; }
    .card-branch { font-size: 12px; color: var(--muted); margin-top: 3px; }
    .card-hint { font-size: 11px; color: var(--indigo); margin-top: 8px; font-weight: 500; }

    /* BACK */
    .back-name { font-size: 15px; font-weight: 600; color: white; }
    .back-branch { font-size: 12px; color: rgba(255,255,255,0.7); margin-bottom: 10px; }
    .back-section-title { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.6); margin-top: 8px; margin-bottom: 4px; }
    .back-bio { font-size: 12px; color: rgba(255,255,255,0.85); line-height: 1.5; }
    .back-tag { font-size: 10.5px; padding: 3px 8px; border-radius: var(--radius-pill); background: rgba(255,255,255,0.15); color: white; }
    .back-links { display: flex; gap: 6px; margin-top: auto; padding-top: 10px; }
    .back-link { font-size: 11.5px; padding: 5px 12px; border-radius: 6px; background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2); transition: background 0.15s; }
    .back-link:hover { background: rgba(255,255,255,0.25); }

    .no-results { text-align:center; padding: 60px 20px; color: var(--muted); }
    .no-results h3 { font-size: 16px; margin-bottom: 6px; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrap">

  <div class="discover-header">
    <h1>Discover Students</h1>
    <p>Find batchmates with similar interests — click a card to see full profile</p>
  </div>

  <div class="filter-wrap">
    <input type="text" class="search-input" id="searchInput" placeholder="🔍  Search by name, branch, or skills..." oninput="filterCards()">
    <button class="filter-chip active" onclick="setFilter(this,'all')">All</button>
    <button class="filter-chip" onclick="setFilter(this,'Computer Science')">CS</button>
    <button class="filter-chip" onclick="setFilter(this,'Information Technology')">IT</button>
    <button class="filter-chip" onclick="setFilter(this,'Electronics')">EC</button>
    <button class="filter-chip" onclick="setFilter(this,'First Year')">First Year</button>
  </div>

  <div class="students-grid" id="studentsGrid">

    <?php $count = 0; while($u = $result->fetch_assoc()): $count++; ?>
    <?php
      $photo = !empty($u['photo']) ? $u['photo'] : '';
      $name = htmlspecialchars($u['name']);
      $branch = htmlspecialchars($u['branch'] ?? '');
      $year = htmlspecialchars($u['year'] ?? '');
      $bio = htmlspecialchars($u['bio'] ?? '');
      $skills = htmlspecialchars($u['skills'] ?? '');
      $interests = htmlspecialchars($u['interests'] ?? '');
      $looking = htmlspecialchars($u['looking_for'] ?? '');
      $linkedin = $u['linkedin'] ?? '';
      $portfolio = $u['portfolio'] ?? '';
      $initials = strtoupper(substr($u['name'],0,1));
    ?>
    <div class="student-card" onclick="this.classList.toggle('flipped')"
         data-name="<?php echo strtolower($u['name']); ?>"
         data-branch="<?php echo strtolower($u['branch'] ?? ''); ?>"
         data-year="<?php echo strtolower($u['year'] ?? ''); ?>"
         data-skills="<?php echo strtolower($u['skills'] ?? ''); ?>">
      <div class="card-inner">
        <!-- FRONT -->
        <div class="card-front">
          <div class="card-photo">
            <?php if($photo): ?>
              <img src="<?php echo htmlspecialchars($photo); ?>" alt="<?php echo $name; ?>">
            <?php else: ?>
              <span style="font-size:44px;opacity:0.4">👤</span>
            <?php endif; ?>
          </div>
          <div class="card-info">
            <div class="card-name"><?php echo $name; ?></div>
            <div class="card-branch"><?php echo $branch; ?> · <?php echo $year; ?></div>
            <?php if($skills): ?>
              <div class="tag-row" style="margin-top:10px">
                <?php foreach(array_slice(explode(',', $u['skills']), 0, 3) as $sk): ?>
                  <span class="tag"><?php echo htmlspecialchars(trim($sk)); ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="card-hint">Click to see full profile →</div>
          </div>
        </div>
        <!-- BACK -->
        <div class="card-back">
          <div class="back-name"><?php echo $name; ?></div>
          <div class="back-branch"><?php echo $branch; ?> · <?php echo $year; ?></div>
          <?php if($bio): ?>
            <div class="back-section-title">Bio</div>
            <div class="back-bio"><?php echo $bio; ?></div>
          <?php endif; ?>
          <?php if($skills): ?>
            <div class="back-section-title">Skills</div>
            <div class="tag-row">
              <?php foreach(array_slice(explode(',', $u['skills']), 0, 4) as $sk): ?>
                <span class="back-tag"><?php echo htmlspecialchars(trim($sk)); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if($interests): ?>
            <div class="back-section-title">Interests</div>
            <div class="tag-row">
              <?php foreach(array_slice(explode(',', $u['interests']), 0, 3) as $int): ?>
                <span class="back-tag"><?php echo htmlspecialchars(trim($int)); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if($looking): ?>
            <div class="back-section-title">Looking For</div>
            <div class="back-bio"><?php echo $looking; ?></div>
          <?php endif; ?>
          <div class="back-links">
            <?php if($linkedin): ?><a href="<?php echo htmlspecialchars($linkedin); ?>" target="_blank" class="back-link" onclick="event.stopPropagation()">LinkedIn</a><?php endif; ?>
            <?php if($portfolio): ?><a href="<?php echo htmlspecialchars($portfolio); ?>" target="_blank" class="back-link" onclick="event.stopPropagation()">Portfolio</a><?php endif; ?>
            <?php if(!$linkedin && !$portfolio): ?><span class="back-bio" style="font-size:11px">No links added yet</span><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>

    <?php if($count === 0): ?>
      <div class="no-results" style="grid-column:1/-1">
        <h3>No students found</h3>
        <p>Students will appear here once they complete their profile.</p>
      </div>
    <?php endif; ?>
  </div>

</div>

<script>
var activeFilter = 'all';

function setFilter(btn, val) {
  activeFilter = val;
  document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
  btn.classList.add('active');
  filterCards();
}

function filterCards() {
  var q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.student-card').forEach(function(card) {
    var name = card.dataset.name || '';
    var branch = card.dataset.branch || '';
    var year = card.dataset.year || '';
    var skills = card.dataset.skills || '';
    var matchQ = !q || name.includes(q) || branch.includes(q) || skills.includes(q);
    var matchF = activeFilter === 'all' || branch.includes(activeFilter.toLowerCase()) || year.includes(activeFilter.toLowerCase());
    card.style.display = (matchQ && matchF) ? '' : 'none';
  });
}
</script>

</body>
</html>
