<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $type = $_POST['type'] ?? '';
  $id = intval($_POST['id'] ?? 0);

  if ($id > 0) {
    switch ($type) {
      case 'contact':
        $stmt = $pdo->prepare("DELETE FROM contact WHERE id = ?");
        $stmt->execute([$id]);
        break;

      case 'user':
        if ($id != $_SESSION['admin_id']) {
          $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
          $stmt->execute([$id]);
        }
        break;

      case 'artist':
        $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE artist_id = ?");
        $stmt->execute([$id]);
        $galleryImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($galleryImages as $img) {
          $imgPath = 'images/gallery/' . $img['image_path'];
          if (file_exists($imgPath)) unlink($imgPath);
        }

        $stmt = $pdo->prepare("DELETE FROM gallery WHERE artist_id = ?");
        $stmt->execute([$id]);

        $stmt = $pdo->prepare("SELECT profile_image FROM artists WHERE id = ?");
        $stmt->execute([$id]);
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($artist && !empty($artist['profile_image'])) {
          $path = 'images/artists/' . $artist['profile_image'];
          if (file_exists($path)) unlink($path);
        }

        $stmt = $pdo->prepare("DELETE FROM artists WHERE id = ?");
        $stmt->execute([$id]);
        break;

      case 'gallery':
        $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($image && !empty($image['image_path'])) {
          $path = 'images/gallery/' . $image['image_path'];
          if (file_exists($path)) unlink($path);
        }
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        break;

      case 'appointment':
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        break;

      case 'flash_tattoo':
        $stmt = $pdo->prepare("SELECT image_path FROM flash_tattoos WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($image && !empty($image['image_path'])) {
          $path = $image['image_path'];
          if (file_exists($path)) unlink($path);
        }
        $stmt = $pdo->prepare("DELETE FROM flash_tattoos WHERE id = ?");
        $stmt->execute([$id]);
        break;
    }
  }
}

header("Location: admin_dashboard.php");
exit;