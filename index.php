<?php
  require_once 'database.php';

  $query = "SELECT * FROM flash_tattoos ORDER BY created_at DESC";
  $statement = $pdo->prepare($query);
  $statement->execute();
  $tattoos = $statement->fetchAll();
  $statement->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ink Soul - Home</title>
  <link rel="stylesheet" href="css/main.css">
</head>

<body class="home-page">

  <?php include("header.php"); ?>

  <main>

    <section class="services-grid">
      <div class="service">
        <img src="images/tattoo.webp" alt="Tattoo Service">
        <div class="service-text">
          <h2>Tattoos</h2>
          <p>Whether you know exactly what kind of tattoo you want or just have a general idea, we can help!</p>
          <a href="contact.php" class="book-button">Book Now</a>
        </div>
      </div>

      <div class="service">
        <img src="images/piercing.webp" alt="Piercing Service">
        <div class="service-text">
          <h2>Piercings</h2>
          <p>Say no to piercing guns and have your piercings done by professionals with years of experience.</p>
          <a href="contact.php" class="book-button">Book Now</a>
        </div>
      </div>

      <div class="service">
        <img src="images/flash_tattoo.png" alt="Piercing Service">
        <div class="service-text">
          <h2>Flash Tattoos</h2>
          <p>New Flash Tattoos by our tattoo artists. Claim your next tattoo today.</p>
          <a href="contact.php" class="book-button">Book Now</a>
        </div>
      </div>
    </section>
  </main>

<section class="flash-sale">
  <h2>Flash Sale Tattoos</h2>
  <div class="flash-sale-wrapper">
    <div class="flash-sale-grid">
      <?php foreach ($tattoos as $tattoo): ?>
        <div class="flash-item">
          <div class="flash-content">
            <img src="<?= htmlspecialchars($tattoo['image_path']) ?>" alt="<?= htmlspecialchars($tattoo['title']) ?>" class="flash-image">
            <p class="flash-info"><?= htmlspecialchars($tattoo['title']) ?> - $<?= htmlspecialchars($tattoo['price']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Buttons moved below -->
  <div class="scroll-controls">
    <button class="scroll-btn left">&larr;</button>
    <button class="scroll-btn right">&rarr;</button>
  </div>
</section>

  <?php include('footer.php'); ?>

</body>
</html>