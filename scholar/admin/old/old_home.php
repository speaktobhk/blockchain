<?php
/**
 * Module: Homepage (index.php)
 * Description: Homepage with live scholarship search integration
 */

require_once 'db.php';
session_start();

// Process search query if submitted
$searchResults = [];
if (!empty($_GET['q'])) {
  $keyword = '%' . trim($_GET['q']) . '%';
  $stmt = $pdo->prepare("SELECT * FROM scholarships WHERE title LIKE ? OR country LIKE ? OR level LIKE ? OR deadline LIKE ? LIMIT 20");
  $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
  $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
require_once 'header.php';
?>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="index.php">ScholarshipFinder</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="jumbotron bg-light py-5 text-center">
    <div class="container">
      <h1 class="display-4">Find Scholarships Easily</h1>
      <p class="lead">Search and apply for scholarships tailored to your goals.</p>
      <form action="" method="get" class="row g-3 justify-content-center">
        <div class="col-md-4">
          <input type="text" class="form-control" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Search by keyword, level, country...">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Search Results -->
  <?php if (!empty($_GET['q'])): ?>
  <section class="container my-5">
    <h3 class="mb-4">Search Results for "<?php echo htmlspecialchars($_GET['q']); ?>"</h3>
    <?php if ($searchResults): ?>
      <div class="row">
        <?php foreach ($searchResults as $row): ?>
          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><strong>Country:</strong> <?php echo $row['country']; ?><br>
                  <strong>Level:</strong> <?php echo $row['level']; ?><br>
                  <strong>Deadline:</strong> <?php echo $row['deadline']; ?>
                </p>
                <a href="scholarship_view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">No scholarships found.</div>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <!-- Footer -->
  <footer class="bg-dark text-white py-4">
    <div class="container text-center">
      &copy; <?php echo date('Y'); ?> ScholarshipFinder. All rights reserved. <br>
      <a href="terms.php" class="text-white">Terms</a> | 
      <a href="privacy.php" class="text-white">Privacy</a> | 
      <a href="contact.php" class="text-white">Contact</a>
    </div>
  </footer>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
