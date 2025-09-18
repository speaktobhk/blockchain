<?php
// bookmarks.php

session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
//   header("Location: login.php");
//   exit();
// }

$user_id = $_SESSION['user_id'];

// Fetch bookmarked scholarships
$stmt = $pdo->prepare("
  SELECT s.*
  FROM bookmarks b
  JOIN scholarships s ON b.scholarship_id = s.id
  WHERE b.user_id = ?
  ORDER BY s.deadline ASC
");
$stmt->execute([$user_id]);
$scholarships = $stmt->fetchAll();

require_once "header.php";
?>


<body>
<div class="container py-4">
  <h3>My Bookmarked Scholarships</h3>

  <?php if (count($scholarships) === 0): ?>
    <p class="text-muted">You haven’t bookmarked any scholarships yet.</p>
  <?php else: ?>
    <div class="row">
      <?php foreach ($scholarships as $s): ?>
        <div class="col-md-6 mb-3">
          <div class="card">
            <div class="card-body">
              <h5><?= htmlspecialchars($s['title']) ?></h5>
              <p><strong>Country:</strong> <?= $s['country'] ?> | <strong>Deadline:</strong> <?= $s['deadline'] ?></p>
              <p><?= substr(htmlspecialchars($s['description']), 0, 100) ?>...</p>
              <a href="scholarship_view.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-info">View</a>
              <a href="bookmark.php?id=<?= $s['id'] ?>&action=remove" class="btn btn-sm btn-outline-danger">Remove</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
