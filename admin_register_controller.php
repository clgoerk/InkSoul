<?php
require("database.php");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $full_name = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $access_code = trim($_POST['access_code']);
  $expected_code = 'SECRET123';
  $role = 'admin';

  if (!empty($username) && !empty($password) && !empty($full_name) && !empty($email) && !empty($access_code)) {
    
    if ($access_code !== $expected_code) {
      $message = "❌ Invalid access code. Admin registration denied.";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, full_name, email) VALUES (?, ?, ?, ?, ?)");

      if ($stmt->execute([$username, $hashedPassword, $role, $full_name, $email])) {
        header("Location: admin_register_success.php");
        exit;
      } else {
        $message = "❌ Error: Could not create account.";
      }
    }

  } else {
    $message = "❌ Please fill in all fields.";
  }
}
?>