<?php
require ("admin_register_controller.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Register - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

  <?php include("header.php"); ?>

  <h2 style="text-align:center;">Create an Administration Account</h2>

  <?php if (!empty($message)): ?>
    <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="" autocomplete="off">
    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Username</label>
    <input type="text" name="username" autocomplete="new-username" required>

    <label>Password</label>
    <input type="password" name="password" autocomplete="new-password" required>

    <label>Access Code</label>
    <input type="text" name="access_code" required>

    <input type="submit" value="Register">
  </form>

  <?php include("footer.php"); ?>
  
</body>
</html>