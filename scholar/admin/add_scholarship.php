<?php
session_start();
require_once '../db.php';

// Protect access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title       = trim($_POST['title'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $level       = $_POST['level'] ?? '';
    $eligibility = trim($_POST['eligibility'] ?? '');
    $deadline    = $_POST['deadline'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $country     = trim($_POST['country'] ?? '');
    $link        = trim($_POST['link'] ?? '');

    if ($title && $type && $level && $eligibility && $deadline && $description && $country && $link) {
        $stmt = $conn->prepare("INSERT INTO scholarships (title, type, level, eligibility, deadline, description, country, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $title, $type, $level, $eligibility, $deadline, $description, $country, $link);

        if ($stmt->execute()) {
            $success = "Scholarship added successfully!";
        } else {
            $error = "Failed to add scholarship. Try again.";
        }
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Scholarship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-success mb-4">➕ Add New Scholarship</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-select" required>
                <option selected disabled>Choose level...</option>
                <option value="bachelor">Bachelor</option>
                <option value="master">Master</option>
                <option value="phd">PhD</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Eligibility</label>
            <textarea name="eligibility" class="form-control" rows="2" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Application Link</label>
            <input type="url" name="link" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Add Scholarship</button>
        <br><br>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary w-100">← Back to Dashboard</a>
    </form>
</div>
</body>
</html>
