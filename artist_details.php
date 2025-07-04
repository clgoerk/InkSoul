<?php
require 'database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
  echo json_encode(['error' => 'Invalid ID']);
  exit;
}

// Get artist details
$stmt = $pdo->prepare("SELECT * FROM artists WHERE id = ?");
$stmt->execute([$id]);
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

// Get tattoos linked to this artist
$stmt = $pdo->prepare("SELECT * FROM gallery WHERE artist_id = ?");
$stmt->execute([$id]);
$tattoos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output JSON
echo json_encode([
  'artist' => $artist,
  'tattoos' => $tattoos
]);
?>