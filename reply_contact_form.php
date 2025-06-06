<?php
require("reply_contact_controller.php");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reply to Contact</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <?php include("header.php"); ?>

  <main class="home-content">
    <h2>Reply to <?= htmlspecialchars($contact['name']) ?></h2>

    <form method="post" action="send_reply.php" class="contact-form">
      <input type="hidden" name="email" value="<?= htmlspecialchars($contact['email']) ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($contact['name']) ?>">

      <label>Subject:</label>
      <input type="text" name="subject" required>

      <label>Your Message:</label>
      <textarea name="message" rows="6" required></textarea>

<div style="display: flex; gap: 10px;">
  <button type="submit" class="button-link" style="flex: 1;">Send Reply</button>
  <a href="admin_dashboard.php" class="button-link" style="flex: 1; text-align: center;">Cancel</a>
</div>
    </form>
  </main>c ,

  <?php include("footer.php"); ?>
</body>
</html>