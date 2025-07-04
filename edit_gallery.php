<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: admin_dashboard.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
$stmt->execute([$id]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
  header("Location: admin_dashboard.php");
  exit;
}

$message = $_SESSION['gallery_message'] ?? '';
unset($_SESSION['gallery_message']);

// Category options
$categories = [
  "Black & Grey", "Color", "Traditional", "Neo-Traditional", "Realism", "Portrait",
  "Fine Line", "Illustrative", "Japanese", "Tribal", "Watercolor", "Geometric",
  "Script", "Minimalist", "Surrealism", "Dotwork", "Biomechanical", "Horror",
  "Mandala", "Ornamental"
];

// Fetch artists for dropdown
$artists = $pdo->query("SELECT id, name FROM artists ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Gallery Image - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <?php include("header.php"); ?>

  <main>
    <form action="edit_gallery_controller.php" method="post" enctype="multipart/form-data" class="gallery-upload-form">
      <h2>Edit Gallery Image</h2>

      <?php if ($message): ?>
        <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <input type="hidden" name="id" value="<?= $image['id'] ?>">

      <label>Title:</label>
      <input type="text" name="title" value="<?= htmlspecialchars($image['title']) ?>" required>

      <label>Description:</label>
      <textarea name="description" rows="4"><?= htmlspecialchars($image['description']) ?></textarea>

      <label>Category:</label>
      <select name="category" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $image['category'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Artist:</label>
      <select name="artist_id">
        <option value="">—</option>
        <?php foreach ($artists as $artist): ?>
          <option value="<?= $artist['id'] ?>" <?= $artist['id'] == $image['artist_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($artist['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Replace Image (optional):</label>
      <input type="file" name="image_file" accept="image/*">

      <input type="submit" value="Update Image">

      <p style="text-align:center; margin-top:15px;">
        <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
      </p>
    </form>
  </main>

  <?php include("footer.php"); ?>
</body>
</html>