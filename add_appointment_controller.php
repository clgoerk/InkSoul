<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('database.php');

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

// Sanitize and gather form inputs
$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$service_type = htmlspecialchars($_POST['service_type'] ?? '');
$appointment_date = $_POST['appointment_date'] ?? '';
$appointment_time = $_POST['appointment_time'] ?? '';
$artist_id = $_POST['artist_id'] ?? '';
$notes = htmlspecialchars($_POST['notes'] ?? '');

// Validate required fields
if ($name && $email && $service_type && $appointment_date && $appointment_time && $artist_id) {
  // Check for duplicate appointment (same artist, date, and time)
  $check = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE artist_id = ? AND appointment_date = ? AND appointment_time = ?");
  $check->execute([$artist_id, $appointment_date, $appointment_time]);
  $alreadyBooked = $check->fetchColumn();

if ($alreadyBooked > 0) {
  // Store form values in session
  $_SESSION['form_data'] = [
    'name' => $name,
    'email' => $email,
    'service_type' => $service_type,
    'appointment_date' => $appointment_date,
    'appointment_time' => $appointment_time,
    'artist_id' => $artist_id,
    'notes' => $notes
  ];
  header("Location: appointment_conflict.php?mode=add");
  exit;
}

  // Insert appointment
  $stmt = $pdo->prepare("INSERT INTO appointments 
    (client_name, client_email, service_type, appointment_date, appointment_time, artist_id, notes, status) 
    VALUES (:name, :email, :service_type, :appointment_date, :appointment_time, :artist_id, :notes, 'approved')");

  $stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':service_type' => $service_type,
    ':appointment_date' => $appointment_date,
    ':appointment_time' => $appointment_time,
    ':artist_id' => $artist_id,
    ':notes' => $notes,
  ]);

  header("Location: appointment_success.php");
  exit;

} else {
  $_SESSION['dashboard_message'] = "❌ All required fields must be filled.";
  header("Location: add_appointment.php");
  exit;
}