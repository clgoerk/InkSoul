<?php
session_start();
require_once('database.php');

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$appointment_id = $_GET['id'] ?? null;
if (!$appointment_id) {
  header("Location: admin_dashboard.php");
  exit;
}

// Fetch appointment
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
  $_SESSION['dashboard_message'] = "❌ Appointment not found.";
  header("Location: admin_dashboard.php");
  exit;
}

// Fetch artists
$artists = $pdo->query("SELECT id, name FROM artists ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Use form data from session if available, otherwise use database values
$form_data = $_SESSION['form_data'] ?? [
  'name' => $appointment['client_name'],
  'email' => $appointment['client_email'],
  'service_type' => $appointment['service_type'],
  'appointment_date' => $appointment['appointment_date'],
  'appointment_time' => $appointment['appointment_time'],
  'artist_id' => $appointment['artist_id'],
  'notes' => $appointment['notes']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Appointment - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php include("header.php"); ?>

<main>
  <h2 style="text-align:center;">✏️ Edit Appointment</h2>

  <form action="edit_appointment_controller.php" method="POST" class="gallery-upload-form">
    <input type="hidden" name="id" value="<?= $appointment['id'] ?>">

    <label>Client Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($form_data['name'] ?? '') ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>

    <label>Service Type</label>
    <input type="text" name="service_type" value="<?= htmlspecialchars($form_data['service_type'] ?? '') ?>" required>

    <label>Appointment Date</label>
    <input type="date" name="appointment_date" value="<?= $form_data['appointment_date'] ?? '' ?>" required>

    <label>Appointment Time</label>
    <input type="time" name="appointment_time" value="<?= $form_data['appointment_time'] ?? '' ?>" required>

    <label>Assign Artist</label>
    <select name="artist_id" required>
      <option value="">-- Select Artist --</option>
      <?php foreach ($artists as $artist): ?>
        <option value="<?= $artist['id'] ?>" <?= ($form_data['artist_id'] ?? '') == $artist['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($artist['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Notes (Optional)</label>
    <textarea name="notes" rows="4"><?= htmlspecialchars($form_data['notes'] ?? '') ?></textarea>

    <input type="submit" value="Update Appointment">

    <p style="text-align:center; margin-top: 20px;">
      <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
    </p>
  </form>
</main>

<?php include("footer.php"); ?>

<?php unset($_SESSION['form_data']); ?>
</body>
</html>