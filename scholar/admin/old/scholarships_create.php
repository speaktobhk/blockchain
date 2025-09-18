<?php
// File: admin_scholarships_create.php
session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//   header('Location: admin_login.php');
//   exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("INSERT INTO scholarships (title, type, level, eligibility, deadline, description, country, link, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
  $stmt->execute([
    $_POST['title'], $_POST['type'], $_POST['level'], $_POST['eligibility'], $_POST['deadline'],
    $_POST['description'], $_POST['country'], $_POST['link']
  ]);
  header('Location: admin_scholarships_list.php');
  exit();
}
require_once 'header.php';
?>

<body>
<div class="container py-4">
  <h2>Add New Scholarship</h2>
  <form method="post" class="border p-3">
    <div class="mb-2">
      <label>Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Type</label>
      <input type="text" name="type" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Level</label>
      <input type="text" name="level" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Eligibility</label>
      <input type="text" name="eligibility" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Deadline</label>
      <input type="date" name="deadline" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Country</label>
      <input type="text" name="country" class="form-control" required>
    </div>
    <div class="mb-2">
      <label>Description</label>
      <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-2">
      <label>Link</label>
      <input type="url" name="link" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Create Scholarship</button>
    <a href="schoolarship.php" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
