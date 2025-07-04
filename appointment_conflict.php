<?php
// appointment_conflict.php
session_start();
require_once('database.php');

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$mode = $_GET['mode'] ?? 'add';
$edit_id = $_GET['id'] ?? null;

// Set the back link based on the mode
$backLink = $mode === 'edit' && $edit_id ? "edit_appointment.php?id=" . urlencode($edit_id) : "add_appointment.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Conflict - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

  <?php include("header.php"); ?>

  <main class="home-content" style="text-align: center;">
    <h2 style="color: red;">❌ Appointment Conflict</h2>
    <p>The selected artist is already booked at the specified date and time.</p>
    <p><a href="<?= $backLink ?>">← Go Back and Modify Appointment</a></p>
  </main>

  <?php include("footer.php"); ?>

</body>
</html>