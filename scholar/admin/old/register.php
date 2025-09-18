<?php
/**
 * Module: Student Login (login.php)
 * Description: Login form with authentication and role-based session handling
 */

require_once 'db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $errors[] = "Please fill in all fields.";
  } else {
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_role'] = $user['role'];

      // Redirect based on role
      if ($user['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit;
      } else {
        header('Location: student/dashboard.php');
        exit;
      }
    } else {
      $errors[] = "Invalid email or password.";
    }
  }
}
require_once 'header.php';
?>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">Student Login</h4>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
              </div>
            <?php endif; ?>

            <form method="post" action="">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
            <p class="text-center"><a href="forgot_password.php">Forgot your password?</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
