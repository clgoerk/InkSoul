<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$contact_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$contact_id) {
  $_SESSION['dashboard_message'] = "Invalid contact ID.";
  header("Location: admin_dashboard.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM contact WHERE id = ?");
$stmt->execute([$contact_id]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$contact) {
  $_SESSION['dashboard_message'] = "Contact not found.";
  header("Location: admin_dashboard.php");
  exit;
}

$artists = $pdo->query("SELECT id, name FROM artists ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Approve Contact - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include("header.php"); ?>

<main>
  <form method="post" action="save_appointment.php" class="gallery-upload-form">
    <h2>Approve Appointment Request</h2>

    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">

    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($contact['name']) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($contact['email']) ?>" required>

    <label>Service Type:</label>
    <input type="text" name="service_type" value="<?= htmlspecialchars($contact['service_type']) ?>">

    <label>Preferred Date:</label>
    <input type="date" name="preferred_date" value="<?= htmlspecialchars($contact['preferred_date']) ?>">

    <label>Preferred Time:</label>
    <input type="time" name="preferred_time" value="<?= htmlspecialchars($contact['preferred_time']) ?>">

    <label>Assign Artist:</label>
    <select name="artist_id" required>
      <option value="">Select Artist</option>
      <?php foreach ($artists as $artist): ?>
        <option value="<?= $artist['id'] ?>"><?= htmlspecialchars($artist['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Notes (Optional)</label>
    <textarea name="notes" rows="4"></textarea>

    <input type="submit" value="Save Appointment">

    <p style="text-align:center; margin-top:15px;">
      <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
    </p>
  </form>
</main>

<?php include("footer.php"); ?>
</body>
</html>