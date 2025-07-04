<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Upload Flash Tattoo</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <?php
  $message = $_SESSION['upload_message'] ?? '';
  unset($_SESSION['upload_message']);
  if ($message):
  ?>
    <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form action="upload_flash_tattoo_controller.php" method="POST" enctype="multipart/form-data" class="gallery-upload-form">
    <h2>Upload Flash Tattoo</h2>
    
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>

    <label for="price">Price</label>
    <input type="text" name="price" id="price" required>

    <label for="image">Image</label>
    <input type="file" name="image" id="image" accept="image/*" required>

    <input type="submit" value="Upload Flash Tattoo">

    <p style="text-align: center; margin-top: 15px;">
      <a href="admin_dashboard.php" class="button-link">← Back to Dashboard</a>
    </p>
  </form>
</main>

<?php include 'footer.php'; ?>

</body>
</html>