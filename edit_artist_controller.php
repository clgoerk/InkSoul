<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$id = $_POST['id'];
$name = htmlspecialchars($_POST['name']);
$specialty = htmlspecialchars($_POST['specialty']);
$bio = htmlspecialchars($_POST['bio']);
$profileImage = null;

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/images/artists/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $filename = basename($_FILES['profile_image']['name']);
  $targetPath = $uploadDir . $filename;
  $counter = 1;

  while (file_exists($targetPath)) {
    $fileInfo = pathinfo($filename);
    $newName = $fileInfo['filename'] . "_" . $counter . "." . $fileInfo['extension'];
    $targetPath = $uploadDir . $newName;
    $counter++;
  }

  $finalFileName = basename($targetPath);
  if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
    $profileImage = $finalFileName;
  }
}

if ($profileImage) {
  $stmt = $pdo->prepare("UPDATE artists SET name = ?, specialty = ?, bio = ?, profile_image = ? WHERE id = ?");
  $stmt->execute([$name, $specialty, $bio, $profileImage, $id]);
} else {
  $stmt = $pdo->prepare("UPDATE artists SET name = ?, specialty = ?, bio = ? WHERE id = ?");
  $stmt->execute([$name, $specialty, $bio, $id]);
}

$_SESSION['artist_message'] = "✅ Artist updated successfully.";
header("Location: edit_artist.php?id=" . $id);
exit;
?>