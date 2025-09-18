<?php
// admin/users_delete.php

session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Get user ID from URL
$user_id = $_GET['id'] ?? 0;
$user_id = (int) $user_id;

// Step 1: Delete related activity logs
$stmt = $pdo->prepare("DELETE FROM activity_logs WHERE user_id = ?");
$stmt->execute([$user_id]);

// Step 2: Delete user
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user_id]);

// Redirect back to user list
header('Location: admin_users_list.php');
exit();
