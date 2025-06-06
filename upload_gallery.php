<?php
require("database.php");
session_start();
$message = $_SESSION['upload_message'] ?? '';
unset($_SESSION['upload_message']);

// Fetch artists from DB
$artistStmt = $pdo->query("SELECT id, name FROM artists ORDER BY name");
$artists = $artistStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Gallery Image - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

  <?php include("header.php"); ?>

  <main>
    <form action="upload_gallery_controller.php" method="post" enctype="multipart/form-data" class="gallery-upload-form">
      <h2>Upload Gallery Image</h2>

      <?php if ($message): ?>
        <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <label>Artist:</label>
      <select name="artist_id" required>
        <option value="">Select an artist</option>
        <?php foreach ($artists as $artist): ?>
          <option value="<?= $artist['id'] ?>"><?= htmlspecialchars($artist['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Title:</label>
      <input type="text" name="title" required>

      <label>Description:</label>
      <textarea name="description" rows="4" required></textarea>

      <label>Select Image:</label>
      <input type="file" name="image" accept="image/*" required>

      <label>Category:</label>
      <select name="category" required>
        <option value="">-- Select Category --</option>
        <option value="Black & Grey">Black & Grey</option>
        <option value="Color">Color</option>
        <option value="Traditional">Traditional</option>
        <option value="Neo-Traditional">Neo-Traditional</option>
        <option value="Realism">Realism</option>
        <option value="Portrait">Portrait</option>
        <option value="Fine Line">Fine Line</option>
        <option value="Illustrative">Illustrative</option>
        <option value="Japanese">Japanese</option>
        <option value="Tribal">Tribal</option>
        <option value="Watercolor">Watercolor</option>
        <option value="Geometric">Geometric</option>
        <option value="Script">Script</option>
        <option value="Minimalist">Minimalist</option>
        <option value="Surrealism">Surrealism</option>
        <option value="Dotwork">Dotwork</option>
        <option value="Biomechanical">Biomechanical</option>
        <option value="Horror">Horror</option>
        <option value="Mandala">Mandala</option>
        <option value="Ornamental">Ornamental</option>
      </select>

      <input type="submit" value="Upload Image">

      <p style="text-align: center; margin-top: 15px;">
        <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
      </p>
    </form>
  </main>

  <?php include("footer.php"); ?>
</body>
</html>