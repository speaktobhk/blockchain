<?php
session_start();
include '../db.php';

// Protect access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get scholarship ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('Invalid scholarship ID'); window.location.href='admin_dashboard.php';</script>";
    exit();
}

// Delete record
$stmt = $conn->prepare("DELETE FROM scholarships WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Scholarship deleted successfully'); window.location.href='admin_dashboard.php';</script>";
} else {
    echo "<script>alert('Failed to delete scholarship'); window.location.href='admin_dashboard.php';</script>";
}
?>
