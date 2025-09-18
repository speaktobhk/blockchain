<?php
session_start();
include 'db.php';

$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Count total records
$countSql = "SELECT COUNT(*) FROM scholarships WHERE level = 'master'";
if ($search) {
    $countSql .= " AND (title LIKE ? OR country LIKE ? OR type LIKE ?)";
    $stmt = $conn->prepare($countSql);
    $like = "%$search%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
} else {
    $result = $conn->query($countSql);
    $total = $result->fetch_row()[0];
}

// Fetch paginated results
$sql = "SELECT * FROM scholarships WHERE level = 'master'";
if ($search) {
    $sql .= " AND (title LIKE ? OR country LIKE ? OR type LIKE ?)";
}
$sql .= " ORDER BY deadline ASC LIMIT $limit OFFSET $offset";

if ($search) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>MSc Scholarships</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="text-success">Master’s Scholarships</h2>

    <form method="GET" class="mb-4">
        <input type="text" name="search" class="form-control" placeholder="Search by title, country, or type..." value="<?= htmlspecialchars($search) ?>">
    </form>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><?= $row['title'] ?> <span class="text-muted float-end"><?= $row['country'] ?></span></h5>
                <p><strong>Type:</strong> <?= $row['type'] ?></p>
                <p><strong>Eligibility:</strong> <?= $row['eligibility'] ?></p>
                <p><strong>Deadline:</strong> <?= $row['deadline'] ?></p>
                <p><?= $row['description'] ?></p>
                <a href="<?= $row['link'] ?>" target="_blank" class="btn btn-sm btn-success">Apply Now</a>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>
