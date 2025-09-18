<?php
// my_applications.php

session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
//     header('Location: my_applications.php');
//     exit();
// }

$user_id = $_SESSION['user_id'];

// Cancel/Delete Application
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $app_id = $_GET['cancel'];
    $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->execute([$app_id, $user_id]);
    header("Location: my_applications.php");
    exit();
}

// Fetch applications
$stmt = $pdo->prepare("
    SELECT a.id, a.status, a.submitted_at, s.title, s.deadline
    FROM applications a
    JOIN scholarships s ON a.scholarship_id = s.id
    WHERE a.user_id = ?
    ORDER BY a.submitted_at DESC
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();

require_once "header.php";

?>


<body>
<div class="container py-4">
  <h3>My Scholarship Applications</h3>

  <?php if (count($applications) === 0): ?>
    <p class="text-muted">You haven’t applied to any scholarships yet.</p>
  <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Scholarship Title</th>
          <th>Deadline</th>
          <th>Status</th>
          <th>Applied On</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($applications as $app): ?>
          <tr>
            <td><?= htmlspecialchars($app['title']) ?></td>
            <td><?= htmlspecialchars($app['deadline']) ?></td>
            <td>
              <span class="badge bg-<?= 
                  $app['status'] === 'approved' ? 'success' : 
                  ($app['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                <?= ucfirst($app['status']) ?>
              </span>
            </td>
            <td><?= date("Y-m-d", strtotime($app['submitted_at'])) ?></td>
            <td>
              <?php if ($app['status'] === 'pending'): ?>
                <a href="?cancel=<?= $app['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this application?')">Cancel</a>
              <?php else: ?>
                <span class="text-muted">N/A</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
