<?php
session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//   header('Location: admin_login.php');
//   exit();
// }

// Get scholarship by ID
if (!isset($_GET['id'])) {
  header('Location: schoolarship.php');
  exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM scholarships WHERE id = ?");
$stmt->execute([$id]);
$scholarship = $stmt->fetch();

if (!$scholarship) {
  echo "Scholarship not found.";
  exit();
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $update = $pdo->prepare("UPDATE scholarships SET title=?, type=?, level=?, eligibility=?, deadline=?, description=?, country=?, link=? WHERE id=?");
  $update->execute([
    $_POST['title'], $_POST['type'], $_POST['level'], $_POST['eligibility'], $_POST['deadline'],
    $_POST['description'], $_POST['country'], $_POST['link'], $id
  ]);
  header('Location: schoolarship.php');
  exit();
}
require_once 'header.php';
?>

<body>
<div class="container py-4">
  <h2>Edit Scholarship</h2>
  <form method="post" class="border p-3">
    <div class="mb-2">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($scholarship['title']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Type</label>
      <input type="text" name="type" class="form-control" value="<?= htmlspecialchars($scholarship['type']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Level</label>
      <input type="text" name="level" class="form-control" value="<?= htmlspecialchars($scholarship['level']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Eligibility</label>
      <input type="text" name="eligibility" class="form-control" value="<?= htmlspecialchars($scholarship['eligibility']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Deadline</label>
      <input type="date" name="deadline" class="form-control" value="<?= $scholarship['deadline'] ?>" required>
    </div>
    <div class="mb-2">
      <label>Country</label>
      <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($scholarship['country']) ?>" required>
    </div>
    <div class="mb-2">
      <label>Description</label>
      <textarea name="description" class="form-control"><?= htmlspecialchars($scholarship['description']) ?></textarea>
    </div>
    <div class="mb-2">
      <label>Link</label>
      <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($scholarship['link']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="admin_scholarships_list.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
