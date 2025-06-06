<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$name = htmlspecialchars($_POST['name']);
$bio = !empty($_POST['bio']) ? htmlspecialchars($_POST['bio']) : null;
$specialty = htmlspecialchars($_POST['specialty']);
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

$query = "INSERT INTO artists (name, bio, specialty, profile_image) VALUES (:name, :bio, :specialty, :profile_image)";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':bio', $bio);
$stmt->bindValue(':specialty', $specialty);
$stmt->bindValue(':profile_image', $profileImage);
$stmt->execute();
$stmt->closeCursor();

$_SESSION['artist_message'] = "✅ Artist added successfully.";
header("Location: add_artist.php");
exit;
?>