<?php
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$adminName = htmlspecialchars($_SESSION['admin_name']);

$contacts = $pdo->query("SELECT * FROM contact ORDER BY submitted_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$gallery = $pdo->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$artists = $pdo->query("SELECT * FROM artists ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

<?php include("header.php"); ?>

<main class="home-content">
  <h2 style="text-align:center;">Welcome, <?= $adminName ?> 👋</h2>
  <p style="text-align:center; margin-top: 10px;">Manage bookings, users, and content submissions below.</p>

  <!-- Contacts -->
  <div class="dashboard-section">
    <h3 class="section-header">📅 Appointment Requests</h3>
    <div class="dashboard-section-content">
      <?php if (count($contacts) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Name</th><th>Email</th><th>Service</th><th>Date</th><th>Time</th><th>Message</th><th>Submitted</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($contacts as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['service_type']) ?></td>
                <td><?= htmlspecialchars($row['preferred_date']) ?></td>
                <td><?= htmlspecialchars($row['preferred_time']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                <td class="action-buttons">
                  <form method="post" action="reply_contact_form.php"><input type="hidden" name="contact_id" value="<?= $row['id'] ?>"><button type="submit" class="edit-btn">Reply</button></form>
                  <form method="post" action="delete.php" onsubmit="return confirm('Are you sure?');"><input type="hidden" name="type" value="contact"><input type="hidden" name="id" value="<?= $row['id'] ?>"><button type="submit" class="delete-btn">Delete</button></form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?><p>No contact/booking requests yet.</p><?php endif; ?>
    </div>
  </div>

  <!-- Users -->
  <div class="dashboard-section">
    <h3 class="section-header">👥 Registered Users</h3>
    <div class="dashboard-section-content">
      <?php if (count($users) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Full Name</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?= htmlspecialchars($user['full_name']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td class="action-buttons">
                  <form method="post" action="delete.php" onsubmit="return confirm('Are you sure?');"><input type="hidden" name="type" value="user"><input type="hidden" name="id" value="<?= $user['id'] ?>"><button type="submit" class="delete-btn">Delete</button></form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?><p>No users registered yet.</p><?php endif; ?>
    </div>
  </div>

  <!-- Artists -->
  <div class="dashboard-section">
    <h3 class="section-header">🎨 Tattoo Artists</h3>
    <div class="dashboard-section-content">
      <p><a href="add_artist.php" class="button-link">+ Add New Artist</a></p>
      <?php if (count($artists) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Image</th><th>Name</th><th>Specialty</th><th>Bio</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($artists as $a): ?>
              <tr>
                <td><?= $a['profile_image'] ? '<img src="images/artists/' . htmlspecialchars($a['profile_image']) . '" alt="Artist Image" style="height:80px;border-radius:5px;">' : '—' ?></td>
                <td><?= htmlspecialchars($a['name']) ?></td>
                <td><?= htmlspecialchars($a['specialty']) ?></td>
                <td><?= nl2br(htmlspecialchars($a['bio'])) ?></td>
                <td class="action-buttons">
                  <form method="get" action="edit_artist.php"><input type="hidden" name="id" value="<?= $a['id'] ?>"><button type="submit" class="edit-btn">Edit</button></form>
                  <form method="post" action="delete.php" onsubmit="return confirm('Delete this artist?');"><input type="hidden" name="type" value="artist"><input type="hidden" name="id" value="<?= $a['id'] ?>"><button type="submit" class="delete-btn">Delete</button></form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?><p>No artists added yet.</p><?php endif; ?>
    </div>
  </div>

  <!-- Gallery -->
  <div class="dashboard-section">
    <h3 class="section-header">🖼️ Gallery Images</h3>
    <div class="dashboard-section-content">
      <p><a href="upload_gallery.php" class="button-link">+ Upload New Image</a></p>
      <?php if (count($gallery) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Image</th><th>Title</th><th>Description</th><th>Category</th><th>Artist ID</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($gallery as $img): ?>
              <tr>
                <td><img src="images/gallery/<?= htmlspecialchars($img['image_path']) ?>" alt="Gallery Image" style="height:80px;"></td>
                <td><?= htmlspecialchars($img['title']) ?></td>
                <td><?= htmlspecialchars($img['description']) ?></td>
                <td><?= htmlspecialchars($img['category']) ?: '—' ?></td>
                <td><?= $img['artist_id'] ?? '—' ?></td>
                <td class="action-buttons">
                  <form method="get" action="edit_gallery.php"><input type="hidden" name="id" value="<?= $img['id'] ?>"><button type="submit" class="edit-btn">Edit</button></form>
                  <form method="post" action="delete.php" onsubmit="return confirm('Delete this image?');"><input type="hidden" name="type" value="gallery"><input type="hidden" name="id" value="<?= $img['id'] ?>"><button type="submit" class="delete-btn">Delete</button></form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?><p>No images uploaded yet.</p><?php endif; ?>
    </div>
  </div>

  <div style="text-align:center; margin-top: 40px;">
    <a href="logout.php" class="button-link">Logout</a>
  </div>
</main>

<?php include("footer.php"); ?>

</body>
</html>