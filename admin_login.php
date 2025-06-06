<?php
require("admin_login_controller.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Login - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

  <?php include("header.php"); ?>

  <h2 style="text-align:center;">Admin Login</h2>

  <?php if (!empty($message)): ?>
    <div class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="" autocomplete="off">
    <label>Username</label>
    <input type="text" name="username" autocomplete="off" required>

    <label>Password</label>
    <input type="password" name="password" autocomplete="off" required>

    <input type="submit" value="Login">
  </form>

  <p style="text-align:center; margin-top: 20px;">
    Don’t have an account? <a href="admin_register.php">Create one</a>
  </p>

  <?php include("footer.php"); ?>

</body>
</html>