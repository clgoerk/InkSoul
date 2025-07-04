<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$id = $_POST['id'] ?? null;
$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$service_type = htmlspecialchars($_POST['service_type'] ?? '');
$appointment_date = $_POST['appointment_date'] ?? '';
$appointment_time = $_POST['appointment_time'] ?? '';
$artist_id = $_POST['artist_id'] ?? '';
$notes = htmlspecialchars($_POST['notes'] ?? '');

// Normalize appointment time to HH:MM:SS
if (strlen($appointment_time) === 5) {
  $appointment_time .= ':00';
}

if ($id && $name && $email && $service_type && $appointment_date && $appointment_time && $artist_id) {
  // Prevent double booking (excluding the current appointment)
  $check = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                          WHERE artist_id = ? AND appointment_date = ? AND appointment_time = ? AND id != ?");
  $check->execute([$artist_id, $appointment_date, $appointment_time, $id]);
  $conflict = $check->fetchColumn();

  if ($conflict > 0) {
    // Save form data to session
    $_SESSION['form_data'] = [
      'name' => $name,
      'email' => $email,
      'service_type' => $service_type,
      'appointment_date' => $appointment_date,
      'appointment_time' => substr($appointment_time, 0, 5), // trim seconds back to HH:MM for input
      'artist_id' => $artist_id,
      'notes' => $notes
    ];
    header("Location: appointment_conflict.php?mode=edit&id=" . urlencode($id));
    exit;
  }

  // Update appointment
  $stmt = $pdo->prepare("UPDATE appointments SET 
    client_name = :name,
    client_email = :email,
    service_type = :service_type,
    appointment_date = :appointment_date,
    appointment_time = :appointment_time,
    artist_id = :artist_id,
    notes = :notes
    WHERE id = :id");

  $stmt->execute([
    ':id' => $id,
    ':name' => $name,
    ':email' => $email,
    ':service_type' => $service_type,
    ':appointment_date' => $appointment_date,
    ':appointment_time' => $appointment_time,
    ':artist_id' => $artist_id,
    ':notes' => $notes
  ]);

  header("Location: appointment_success.php");
  exit;

} else {
  $_SESSION['dashboard_message'] = "❌ Missing required fields.";
  header("Location: edit_appointment.php?id=" . urlencode($id));
  exit;
}