<?php
session_start();
require_once '../db.php';

// Protect access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get scholarship ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM scholarships WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$scholarship = $result->fetch_assoc();

if (!$scholarship) {
    echo "<script>alert('Scholarship not found'); window.location.href='admin_dashboard.php';</script>";
    exit();
}

// Handle update
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
        $update = $conn->prepare("UPDATE scholarships SET title=?, type=?, level=?, eligibility=?, deadline=?, description=?, country=?, link=? WHERE id=?");
        $update->bind_param("ssssssssi", $title, $type, $level, $eligibility, $deadline, $description, $country, $link, $id);

        if ($update->execute()) {
            $success = "Scholarship updated successfully!";
            // Refresh data
            $scholarship = [
                'title' => $title,
                'type' => $type,
                'level' => $level,
                'eligibility' => $eligibility,
                'deadline' => $deadline,
                'description' => $description,
                'country' => $country,
                'link' => $link
            ];
        } else {
            $error = "Update failed. Try again.";
        }
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Scholarship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-warning mb-4">✏️ Edit Scholarship</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($scholarship['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" value="<?= htmlspecialchars($scholarship['type']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-select" required>
                <option value="bachelor" <?= $scholarship['level'] == 'bachelor' ? 'selected' : '' ?>>Bachelor</option>
                <option value="master" <?= $scholarship['level'] == 'master' ? 'selected' : '' ?>>Master</option>
                <option value="phd" <?= $scholarship['level'] == 'phd' ? 'selected' : '' ?>>PhD</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Eligibility</label>
            <textarea name="eligibility" class="form-control" rows="2" required><?= htmlspecialchars($scholarship['eligibility']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control" value="<?= htmlspecialchars($scholarship['deadline']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($scholarship['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($scholarship['country']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Application Link</label>
            <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($scholarship['link']) ?>" required>
        </div>
        <button type="submit" class="btn btn-warning w-100">Update Scholarship</button>
        <br><br>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary w-100">← Back to Dashboard</a>
    </form>
</div>
</body>
</html>
