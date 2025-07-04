<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$id = $_POST['id'] ?? null;
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category = trim($_POST['category'] ?? '');
$artist_id = $_POST['artist_id'] !== '' ? intval($_POST['artist_id']) : null;

if (!$id || $title === '') {
  $_SESSION['gallery_message'] = "❌ Title is required.";
  header("Location: edit_gallery.php?id=" . urlencode($id));
  exit;
}

// Fetch current image path from DB
$stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
$stmt->execute([$id]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImage = $current['image_path'] ?? null;

// Default to existing image unless replaced
$image_path = $currentImage;

if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/images/gallery/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $originalName = basename($_FILES['image_file']['name']);
  $targetPath = $uploadDir . $originalName;
  $counter = 1;

  // Ensure unique filename
  while (file_exists($targetPath)) {
    $fileInfo = pathinfo($originalName);
    $newName = $fileInfo['filename'] . "_" . $counter . "." . $fileInfo['extension'];
    $targetPath = $uploadDir . $newName;
    $counter++;
  }

  $finalFileName = basename($targetPath);

  if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
    // Delete old image if different
    if ($currentImage && file_exists($uploadDir . $currentImage) && $currentImage !== $finalFileName) {
      unlink($uploadDir . $currentImage);
    }
    $image_path = $finalFileName;
  } else {
    $_SESSION['gallery_message'] = "❌ Failed to upload new image.";
    header("Location: edit_gallery.php?id=" . urlencode($id));
    exit;
  }
}

// Update the gallery record
$stmt = $pdo->prepare("
  UPDATE gallery 
  SET title = ?, description = ?, category = ?, artist_id = ?, image_path = ?
  WHERE id = ?
");
$stmt->execute([$title, $description, $category, $artist_id, $image_path, $id]);

$_SESSION['gallery_message'] = "✅ Image updated successfully.";
header("Location: edit_gallery.php?id=" . urlencode($id));
exit;