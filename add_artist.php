<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$message = $_SESSION['artist_message'] ?? '';
unset($_SESSION['artist_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Artist - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <?php include("header.php"); ?>

  <main>
    <form action="add_artist_controller.php" method="post" enctype="multipart/form-data" class="gallery-upload-form">
      <h2>Add New Artist</h2>

      <?php if ($message): ?>
        <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <label>Name:</label>
      <input type="text" name="name" required>

      <label>Bio (optional):</label>
      <textarea name="bio" rows="4"></textarea>

      <label>Specialty:</label>
      <input type="text" name="specialty" required>

      <label>Profile Image (optional):</label>
      <input type="file" name="profile_image" accept="image/*">

      <input type="submit" value="Add Artist">

      <p style="text-align:center; margin-top:15px;">
        <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
      </p>
    </form>
  </main>

  <?php include("footer.php"); ?>
</body>
</html>