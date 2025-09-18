<?php
// ask_advisor.php

session_start();
require_once 'db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
//   header("Location: login.php");
//   exit();
// }

$user_id = $_SESSION['user_id'];
$msg = '';

if (isset($_POST['ask'])) {
  $question = trim($_POST['question']);

  if ($question) {
    $stmt = $pdo->prepare("INSERT INTO advisor_questions (user_id, question) VALUES (?, ?)");
    $stmt->execute([$user_id, $question]);
    $msg = "Your question has been submitted!";
  } else {
    $msg = "Please enter a question.";
  }
}

// Fetch previously asked
$stmt = $pdo->prepare("SELECT * FROM advisor_questions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$questions = $stmt->fetchAll();

require_once "header.php";
?>

<body>
<div class="container py-4">
  <h3>Ask Financial Aid Advisor</h3>

  <?php if ($msg): ?>
    <div class="alert alert-info"><?= $msg ?></div>
  <?php endif; ?>

  <form method="post" class="mb-4">
    <textarea name="question" class="form-control" placeholder="Type your question here..." required></textarea>
    <button type="submit" name="ask" class="btn btn-primary mt-2">Submit Question</button>
  </form>

  <?php if ($questions): ?>
    <h5>Your Previous Questions</h5>
    <ul class="list-group">
      <?php foreach ($questions as $q): ?>
        <li class="list-group-item">
          <strong>Q:</strong> <?= htmlspecialchars($q['question']) ?><br>
          <strong>A:</strong> <?= $q['answer'] ? htmlspecialchars($q['answer']) : '<em>Not answered yet</em>' ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
</body>
</html>
