<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $service_type = trim($_POST['service_type']);
  $preferred_date = $_POST['preferred_date'];
  $preferred_time = $_POST['preferred_time'];
  $artist_id = filter_input(INPUT_POST, 'artist_id', FILTER_VALIDATE_INT);
  $notes = trim($_POST['notes']);

  // Validate required fields
  if (!$name || !$email || !$artist_id || !$preferred_date || !$preferred_time || !$service_type) {
    $_SESSION['dashboard_message'] = "❌ Missing required fields.";
    header("Location: approve_contact.php?id=" . urlencode($contact_id));
    exit;
  }

  // Check for conflicting appointment
  $check = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                          WHERE artist_id = ? AND appointment_date = ? AND appointment_time = ?");
  $check->execute([$artist_id, $preferred_date, $preferred_time]);
  $conflict = $check->fetchColumn();

  if ($conflict > 0) {
    $_SESSION['dashboard_message'] = "❌ That artist is already booked for that time.";
    header("Location: approve_contact.php?id=" . urlencode($contact_id));
    exit;
  }

  // Insert into appointments table
  $stmt = $pdo->prepare("
    INSERT INTO appointments (
      client_name, client_email, artist_id,
      appointment_date, appointment_time,
      service_type, notes, status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')
  ");
  $stmt->execute([
    $name, $email, $artist_id,
    $preferred_date, $preferred_time,
    $service_type, $notes
  ]);

  // Update contact status
  if ($contact_id) {
    $update = $pdo->prepare("UPDATE contact SET status = 'approved' WHERE id = ?");
    $update->execute([$contact_id]);
  }

  // ✅ Redirect to success page instead of dashboard
  header("Location: appointment_success.php");
  exit;
}

header("Location: admin_dashboard.php");
exit;