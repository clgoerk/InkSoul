<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ink Soul - Home</title>
  <link rel="stylesheet" href="css/main.css">
</head>

<body>
  
  <?php include("header.php"); ?>

  <main class="home-content">
    <section class="intro">
      <h2>Welcome to Ink Soul<br><span>Where Art Meets Skin</span></h2>
      <p>
        At Ink Soul, we believe every tattoo tells a story — a symbol of strength, memory, passion, or transformation.
        Located in the heart of Toronto, our studio brings together skilled artists, a commitment to hygiene and safety,
        and a deep respect for the art of tattooing.
      </p>
      <p>
        Explore our artists, browse our gallery, and book your session today.
        <strong>Your skin is your canvas — let’s create something unforgettable.</strong>
      </p>
    </section>
    <section class="services-grid">
      <div class="service">
        <a href="tattoo.php" class="service-link">
          <img src="images/tattoo.webp" alt="Tattoo Service">
          <h3>Tattoos</h3>
          <p>We’ve answered your most frequently asked tattoo questions and everything about tattoo aftercare.</p>
        </a>
      </div>
      <div class="service">
        <img src="images/piercing.webp" alt="Piercing Service">
        <h3>Piercings</h3>
        <p>We’ve gathered our piercing aftercare guide, FAQs, and our piercer’s portfolios for inspiration.</p>
      </div>
    </section>
  </main>

  <?php include('footer.php'); ?>

</body>
</html>
