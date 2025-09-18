<?php
session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//   header('Location: admin_login.php');
//   exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
  $stmt->execute([
    $_POST['full_name'],
    $_POST['email'],
    password_hash($_POST['password'], PASSWORD_BCRYPT),
    $_POST['role'],
    $_POST['status']
  ]);
  header("Location: users_list.php");
  exit();
}
require_once 'header.php';
?>

<body>
<div class="container py-4">
  <h2>Add New User</h2>
  <form method="post" class="border p-3">
    <div class="mb-2">
      <label>Full Name</label>
      <input type="text" name="full_name" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Role</label>
      <select name="role" class="form-control">
        <option value="student">Student</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="mb-2">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>
    <button class="btn btn-success">Create</button>
    <a href="users_list.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
