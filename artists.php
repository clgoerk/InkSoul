<?php
session_start();
require_once 'database.php';

// Get all artists
$query = "SELECT * FROM artists ORDER BY name";
$statement = $pdo->prepare($query);
$statement->execute();
$artists = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Ink Soul - Our Artists</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <h2 class="gallery-title">Meet the Crew</h2>
  <p class="artist-intro">Click an artist to get a full bio, view their work and book an appointment</p>
  <p class="artist-tagline">Discover our talented artists and their unique styles — your story begins here.</p>

  <div class="artist-list">
    <?php foreach ($artists as $artist): ?>
      <div class="artist-card" onclick="loadArtist(<?= $artist['id'] ?>)" style="cursor: pointer;">
        <?php if (!empty($artist['profile_image'])): ?>
          <img src="images/artists/<?= htmlspecialchars($artist['profile_image']) ?>"
               alt="<?= htmlspecialchars($artist['name']) ?>"
               class="artist-photo">
        <?php else: ?>
          <div class="artist-placeholder">No Image</div>
        <?php endif; ?>
        <div class="artist-name"><?= htmlspecialchars($artist['name']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Artist Detail Section -->
  <div id="artistDetail" class="artist-detail">
    <div id="artistProfile"></div>
    <div id="tattooGallery" class="tattoo-gallery"></div>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>