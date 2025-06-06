<?php
require("database.php");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (!empty($username) && !empty($password)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
      session_start();
      $_SESSION['admin_id'] = $user['id'];
      $_SESSION['admin_name'] = $user['full_name'];
      header("Location: admin_dashboard.php");
      exit;
    } else {
      $message = "❌ Invalid credentials.";
    }
  } else {
    $message = "❌ Please fill in all fields.";
  }
}