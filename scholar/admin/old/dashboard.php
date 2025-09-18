<?php
/**
 * File: admin_dashboard.php
 * Description: Secure admin dashboard with stats, activity log, and search
 */

session_start();
require_once 'db.php';

// Check admin login
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//   header('Location: admin_login.php');
//   exit();
// }

// // Fetch stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$total_scholarships = $pdo->query("SELECT COUNT(*) FROM scholarships")->fetchColumn();
$new_applications = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'")->fetchColumn();
$unanswered = $pdo->query("SELECT COUNT(*) FROM questions WHERE status = 'open'")->fetchColumn();

// Search filter for activities
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
  $stmt = $pdo->prepare("SELECT al.*, u.name FROM activity_logs al JOIN users u ON u.id = al.user_id WHERE u.name LIKE ? OR al.action LIKE ? OR al.details LIKE ? ORDER BY al.timestamp DESC LIMIT 20");
  $stmt->execute(["%$search%", "%$search%", "%$search%"]);
  $activities = $stmt->fetchAll();
} else {
  $activities = $pdo->query("SELECT al.*, u.name FROM activity_logs al JOIN users u ON u.id = al.user_id ORDER BY al.timestamp DESC LIMIT 10")->fetchAll();
}
require_once 'header.php';
?>

<body>
  <div class="container py-4">
    <h2 class="mb-4">Admin Dashboard</h2>
    <div class="row text-center">
      <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
          <div class="card-body">
            <h5>Total Students</h5>
            <h2><?= $total_users ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
          <div class="card-body">
            <h5>Scholarships</h5>
            <h2><?= $total_scholarships ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning text-white mb-3">
          <div class="card-body">
            <h5>New Applications</h5>
            <h2><?= $new_applications ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-danger text-white mb-3">
          <div class="card-body">
            <h5>Unanswered Qs</h5>
            <h2><?= $unanswered ?></h2>
          </div>
        </div>
      </div>
    </div>

    <h4 class="mt-5">Recent Activities</h4>
    <form method="get" class="mb-3">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search activities..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-outline-primary">Search</button>
      </div>
    </form>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>User</th>
          <th>Action</th>
          <th>Details</th>
          <th>Timestamp</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($activities as $log): ?>
          <tr>
            <td><?= htmlspecialchars($log['name']) ?></td>
            <td><?= htmlspecialchars($log['action']) ?></td>
            <td><?= htmlspecialchars($log['details']) ?></td>
            <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
d