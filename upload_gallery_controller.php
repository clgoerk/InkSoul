<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$_SESSION['upload_message'] = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $artist_id = isset($_POST['artist_id']) && $_POST['artist_id'] !== '' ? (int)$_POST['artist_id'] : null;
  $title = htmlspecialchars($_POST['title']);
  $description = htmlspecialchars($_POST['description']);
  $category = $_POST['category'];

  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/images/gallery/';
    $filename = basename($_FILES['image']['name']);
    $target_path = $upload_dir . $filename;

    // Ensure the directory exists
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
      // Insert data into gallery
      $query = "INSERT INTO gallery (artist_id, image_path, title, description, category)
                VALUES (:artist_id, :image_path, :title, :description, :category)";
      $stmt = $pdo->prepare($query);
      $stmt->bindValue(':artist_id', $artist_id, $artist_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
      $stmt->bindValue(':image_path', $filename);
      $stmt->bindValue(':title', $title);
      $stmt->bindValue(':description', $description);
      $stmt->bindValue(':category', $category);
      $stmt->execute();
      $stmt->closeCursor();

      $_SESSION['upload_message'] = "✅ Image uploaded successfully.";
    } else {
      $_SESSION['upload_message'] = "❌ Failed to move uploaded file. Check folder permissions or filename.";
    }
  } else {
    $_SESSION['upload_message'] = "❌ Please select an image to upload.";
  }

  header("Location: upload_gallery.php");
  exit;
}
?>