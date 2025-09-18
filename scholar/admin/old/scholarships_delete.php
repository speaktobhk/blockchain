<?php
// File: admin_scholarships_delete.php
session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//   header('Location: admin_login.php');
//   exit();
// }

if (isset($_GET['id'])) {
  $stmt = $pdo->prepare("DELETE FROM scholarships WHERE id = ?");
  $stmt->execute([$_GET['id']]);
}

header('Location: schoolarship.php');
exit();
