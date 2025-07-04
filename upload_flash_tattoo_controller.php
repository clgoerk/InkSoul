<?php
session_start();
require_once 'database.php';

// Ensure admin
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$title = trim($_POST['title']);
$price = trim($_POST['price']);
$image = $_FILES['image'];

// Validate inputs
if (!$title || !$price || !$image) {
  $_SESSION['upload_message'] = "❌ All fields are required.";
  header("Location: upload_flash_tattoo_form.php");
  exit;
}

// Setup upload path
$target_dir = "images/flash/";
if (!is_dir($target_dir)) {
  mkdir($target_dir, 0755, true);
}

$filename = uniqid() . "_" . basename($image["name"]);
$target_file = $target_dir . $filename;

// Upload and DB insert
if (move_uploaded_file($image["tmp_name"], $target_file)) {
  $query = "INSERT INTO flash_tattoos (title, price, image_path) VALUES (:title, :price, :image_path)";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':title', $title);
  $stmt->bindParam(':price', $price, PDO::PARAM_STR);
  $stmt->bindParam(':image_path', $target_file);
  $stmt->execute();

  $_SESSION['upload_message'] = "✅ Flash tattoo uploaded successfully!";
} else {
  $_SESSION['upload_message'] = "❌ Failed to upload image.";
}

header("Location: upload_flash_tattoo_form.php");
exit;