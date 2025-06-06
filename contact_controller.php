<?php
require("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $service_type = trim($_POST['service_type']);
  $preferred_date = $_POST['preferred_date'];
  $preferred_time = $_POST['preferred_time'];
  $message = trim($_POST['message']);

  if (!empty($name) && !empty($email) && !empty($service_type) && !empty($preferred_date) && !empty($preferred_time) && !empty($message)) {
    try {
      $stmt = $pdo->prepare("
        INSERT INTO contact (name, email, service_type, preferred_date, preferred_time, message) 
        VALUES (?, ?, ?, ?, ?, ?)
      ");

      if ($stmt->execute([$name, $email, $service_type, $preferred_date, $preferred_time, $message])) {
        header("Location: contact_success.php");
        exit;
      } else {
        echo "❌ Error: Failed to save your request.";
      }
    } catch (PDOException $e) {
      echo "❌ Database error: " . htmlspecialchars($e->getMessage());
    }
  } else {
    echo "❌ Please fill in all fields.";
  }
} else {
  header("Location: contact.php");
  exit;
}