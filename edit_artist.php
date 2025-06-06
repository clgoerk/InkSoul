<?php
require("database.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$message = $_SESSION['artist_message'] ?? '';
unset($_SESSION['artist_message']);

$artist_id = $_GET['id'] ?? null;
if (!$artist_id) {
  header("Location: admin_dashboard.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM artists WHERE id = ?");
$stmt->execute([$artist_id]);
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artist) {
  header("Location: admin_dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Artist</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <?php include("header.php"); ?>
  <main>
    <form action="edit_artist_controller.php" method="post" enctype="multipart/form-data" class="gallery-upload-form">
      <h2>Edit Artist</h2>

      <?php if ($message): ?>
        <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <input type="hidden" name="id" value="<?= $artist['id'] ?>">

      <label>Name:</label>
      <input type="text" name="name" value="<?= htmlspecialchars($artist['name']) ?>" required>

      <label>Specialty:</label>
      <textarea name="specialty" rows="2" required><?= htmlspecialchars($artist['specialty']) ?></textarea>

      <label>Bio:</label>
      <textarea name="bio" rows="4"><?= htmlspecialchars($artist['bio']) ?></textarea>

      <label>Profile Image:</label>
      <input type="file" name="profile_image">
      <?php if (!empty($artist['profile_image'])): ?>
        <p>Current Image: <?= htmlspecialchars($artist['profile_image']) ?></p>
      <?php endif; ?>

      <input type="submit" value="Update Artist">
      <p style="text-align:center; margin-top:15px;">
        <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
      </p>
    </form>
  </main>
  <?php include("footer.php"); ?>
</body>
</html>