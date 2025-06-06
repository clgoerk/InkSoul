<?php
// reply_contact_controller.php
session_start();
require("database.php");

// Ensure only logged-in admins can access
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

// Initialize variable
$contact = null;

// Handle POST request with contact ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_id'])) {
  $contact_id = intval($_POST['contact_id']);

  $stmt = $pdo->prepare("SELECT * FROM contact WHERE id = ?");
  $stmt->execute([$contact_id]);
  $contact = $stmt->fetch();

  if (!$contact) {
    die("❌ Contact not found.");
  }
} else {
  // Redirect if accessed incorrectly
  header("Location: admin_dashboard.php");
  exit;
}