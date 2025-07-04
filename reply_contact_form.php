<?php
require("database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['contact_id'])) {
  $stmt = $pdo->prepare("SELECT * FROM contact WHERE id = ?");
  $stmt->execute([$_POST['contact_id']]);
  $contact = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$contact) {
    echo "Contact not found.";
    exit;
  }

  // Mark as read if status is new
  if ($contact['status'] === 'new') {
    $updateStmt = $pdo->prepare("UPDATE contact SET status = 'read' WHERE id = ?");
    $updateStmt->execute([$contact['id']]);
    $contact['status'] = 'read';
  }
} else {
  header("Location: admin_dashboard.php");
  exit;
}
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

  <form method="post" action="send_reply.php" class="contact-form">
    <h2>Reply to <?= htmlspecialchars($contact['name']) ?></h2>

    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
    <input type="hidden" name="email" value="<?= htmlspecialchars($contact['email']) ?>">
    <input type="hidden" name="name" value="<?= htmlspecialchars($contact['name']) ?>">

    <p><strong>Name:</strong> <?= htmlspecialchars($contact['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?></p>
    <p><strong>Service:</strong> <?= htmlspecialchars($contact['service_type']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($contact['preferred_date']) ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($contact['preferred_time']) ?></p>
    <p><strong>Submitted:</strong> <?= htmlspecialchars($contact['submitted_at']) ?></p>
    <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($contact['message'])) ?></p>

    <label for="subject">Subject:</label>
    <input type="text" name="subject" id="subject" required>

    <label for="message">Your Message:</label>
    <textarea name="message" id="message" rows="6" required></textarea>

    <input type="submit" value="Send Reply">

    <p style="text-align: center; margin-top: 15px;">
      <a href="admin_dashboard.php" class="button-link back-home">← Return to Dashboard</a>
    </p>
  </form>

  <?php include("footer.php"); ?>
</body>
</html>