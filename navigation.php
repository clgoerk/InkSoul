<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<nav class="main-nav">
  <div id="mobileMenu">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </div>

  <ul id="navList" class="nav-links">
    <li><a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a></li>
    <li><a href="artists.php" class="<?= $current_page === 'artists.php' ? 'active' : '' ?>">Artists</a></li>
    <li><a href="gallery.php" class="<?= $current_page === 'gallery.php' ? 'active' : '' ?>">Gallery</a></li>
    <li><a href="services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Services</a></li>
    <li><a href="contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">Book</a></li>
  </ul>
</nav>
