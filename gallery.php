<?php
require("database.php");

$selectedCategory = $_GET['category'] ?? '';

$query = "
  SELECT gallery.*, artists.name AS artist_name
  FROM gallery
  JOIN artists ON gallery.artist_id = artists.id
";
if (!empty($selectedCategory)) {
  $query .= " WHERE gallery.category = :category";
}
$query .= " ORDER BY gallery.id DESC";

$statement = $pdo->prepare($query);
if (!empty($selectedCategory)) {
  $statement->bindValue(':category', $selectedCategory);
}
$statement->execute();
$images = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

$categories = [
  "Black & Grey", "Color", "Traditional", "Neo-Traditional", "Realism", "Portrait",
  "Fine Line", "Illustrative", "Japanese", "Tribal", "Watercolor", "Geometric",
  "Script", "Minimalist", "Surrealism", "Dotwork", "Biomechanical", "Horror",
  "Mandala", "Ornamental"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gallery - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/css/lightbox.min.css">
</head>
<body>

  <?php include("header.php"); ?>

  <main class="gallery-page">
    <h2 class="gallery-title">Check out some of our recent tattoos</h2>

    <form method="get" class="gallery-filter-form">
      <label for="category">Filter by Category:</label>
      <select name="category" id="category" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $selectedCategory ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <div class="gallery-grid">
      <?php foreach ($images as $img): ?>
        <div class="gallery-item">
          <a href="images/gallery/<?= htmlspecialchars($img['image_path']) ?>"
             data-lightbox="gallery"
             data-title="<?= htmlspecialchars($img['title']) ?> - <?= htmlspecialchars($img['artist_name']) ?>">
            <img src="images/gallery/<?= htmlspecialchars($img['image_path']) ?>" alt="<?= htmlspecialchars($img['title']) ?>">
          </a>
          <div class="gallery-info">
            <h3><?= htmlspecialchars($img['title']) ?></h3>
            <p><?= htmlspecialchars($img['description']) ?></p>
            <p><strong>Artist:</strong> <?= htmlspecialchars($img['artist_name']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($img['category']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <?php include("footer.php"); ?>
  <script src="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/js/lightbox.min.js"></script>

</body>
</html>