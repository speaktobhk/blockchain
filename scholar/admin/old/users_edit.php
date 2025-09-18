<?php
session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//   header('Location: admin_login.php');
//   exit();
// }

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
  echo "User not found.";
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, role=?, status=? WHERE id=?");
  $stmt->execute([
    $_POST['name'], $_POST['email'], $_POST['role'], $_POST['status'], $id
  ]);
  header("Location: users_list.php");
  exit();
}
require_once 'header.php';
?>

<body>
<div class="container py-4">
  <h2>Edit User</h2>
  <form method="post" class="border p-3">
    <div class="mb-2">
      <label>Full Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Role</label>
      <select name="role" class="form-control">
        <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Student</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>
    <div class="mb-2">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="users_list.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
