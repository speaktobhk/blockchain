<?php
require_once "db.php";
$search = $_GET['search'] ?? '';
if ($search) {
  $stmt = $pdo->prepare("SELECT * FROM scholarships WHERE title LIKE ? OR country LIKE ? ORDER BY deadline ASC");
  $stmt->execute(["%$search%", "%$search%"]);
  $scholarships = $stmt->fetchAll();
} else {
  $scholarships = $pdo->query("SELECT * FROM scholarships ORDER BY deadline ASC")->fetchAll();
}
require_once 'header.php';
?>

<body>
<div class="container py-4">
  <h2>Scholarship List</h2>
  <form method="get" class="mb-3">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search by title or country..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-outline-primary">Search</button>
    </div>
  </form>
  <a href="scholarships_create.php" class="btn btn-success mb-3">+ Add New</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Title</th>
        <th>Type</th>
        <th>Level</th>
        <th>Deadline</th>
        <th>Country</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($scholarships as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['title']) ?></td>
          <td><?= htmlspecialchars($s['type']) ?></td>
          <td><?= htmlspecialchars($s['level']) ?></td>
          <td><?= htmlspecialchars($s['deadline']) ?></td>
          <td><?= htmlspecialchars($s['country']) ?></td>
          <td>
            <a href="scholarships_edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="scholarships_delete.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this scholarship?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
