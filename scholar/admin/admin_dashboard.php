<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Total scholarships
$total = $conn->query("SELECT COUNT(*) FROM scholarships")->fetch_row()[0];

// Count by level
$levels = ['bachelor', 'master', 'phd'];
$levelCounts = [];
foreach ($levels as $level) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM scholarships WHERE level = ?");
    $stmt->bind_param("s", $level);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $levelCounts[$level] = $count;
    $stmt->close();
}

// Top countries
$countryResult = $conn->query("SELECT country, COUNT(*) as total FROM scholarships GROUP BY country ORDER BY total DESC LIMIT 5");

// Upcoming deadlines
$deadlineResult = $conn->query("SELECT title, deadline FROM scholarships WHERE deadline >= CURDATE() ORDER BY deadline ASC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-primary mb-4">📊 Admin Analytics Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Scholarships</h5>
                    <p class="card-text fs-4"><?= $total ?></p>
                </div>
            </div>
        </div>
        <?php foreach ($levelCounts as $level => $count): ?>
            <div class="col-md-3">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= ucfirst($level) ?> Level</h5>
                        <p class="card-text fs-4"><?= $count ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h4 class="text-success">🌍 Top Countries</h4>
    <ul class="list-group mb-4">
        <?php while ($row = $countryResult->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $row['country'] ?>
                <span class="badge bg-primary rounded-pill"><?= $row['total'] ?></span>
            </li>
        <?php endwhile; ?>
    </ul>

    <h4 class="text-danger">📅 Upcoming Deadlines</h4>
    <ul class="list-group">
        <?php while ($row = $deadlineResult->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?= $row['title'] ?></strong> — <?= $row['deadline'] ?>
            </li>
        <?php endwhile; ?>
    </ul>

    
</div>
</body>
</html>
