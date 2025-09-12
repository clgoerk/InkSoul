<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require("database.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit;
}

$adminName = htmlspecialchars($_SESSION['admin_name']);

$contacts = $pdo->query("
  SELECT c.*, a.name AS artist_name
  FROM contact c
  LEFT JOIN artists a ON c.artist_id = a.id
  ORDER BY c.submitted_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$gallery = $pdo->query("
  SELECT gallery.*, artists.name AS artist_name 
  FROM gallery 
  LEFT JOIN artists ON gallery.artist_id = artists.id 
  ORDER BY gallery.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$artists = $pdo->query("SELECT * FROM artists ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$appointments = $pdo->query("
  SELECT 
    a.id, 
    a.client_name, 
    a.client_email, 
    a.service_type, 
    a.appointment_date, 
    a.appointment_time, 
    a.notes, 
    ar.name AS artist_name
  FROM appointments a
  JOIN artists ar ON a.artist_id = ar.id
  ORDER BY a.appointment_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

/** helper for 12-hour time with no seconds */
function t12($timeStr) {
  if (!$timeStr) return '';
  $ts = strtotime($timeStr);
  return $ts ? date("g:i A", $ts) : htmlspecialchars($timeStr);
}
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

<main>
  <div class="dashboard-container">
    <h2 style="text-align:center;">Welcome, <?= $adminName ?> 👋</h2>
    <p style="text-align:center; margin-top: 10px;">Manage bookings, users, and content submissions below.</p>

    <!-- Appointment Requests -->
    <div class="dashboard-section">
      <h3 class="section-header">📅 Appointment Requests</h3>
      <div class="dashboard-section-content">
        <?php if (count($contacts) > 0): ?>
          <table class="admin-table">
            <thead>
              <tr>
                <th class="col-name">Name</th>
                <th class="col-email">Email</th>
                <th class="col-service">Service</th>
                <th class="col-artist">Artist</th>
                <th class="col-date">Date</th>
                <th class="col-time">Time</th>
                <th class="col-message">Message</th>
                <th class="col-status">Status</th>
                <th class="col-submitted">Submitted</th>
                <th class="col-actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($contacts as $row): ?>
                <tr>
                  <td class="col-name"><?= htmlspecialchars($row['name']) ?></td>
                  <td class="col-email"><?= htmlspecialchars($row['email']) ?></td>
                  <td class="col-service"><?= htmlspecialchars($row['service_type']) ?></td>
                  <td class="col-artist"><?= htmlspecialchars($row['artist_name'] ?? '—') ?></td>
                  <td class="col-date"><?= htmlspecialchars($row['preferred_date']) ?></td>
                  <td class="col-time"><?= t12($row['preferred_time']) ?></td>
                  <td class="col-message"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                  <td class="col-status status-cell">
                    <span class="status-label <?= htmlspecialchars($row['status']) ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>
                  <td class="col-submitted"><?= htmlspecialchars($row['submitted_at']) ?></td>
                  <td class="col-actions action-buttons">
                    <form method="post" action="reply_contact_form.php">
                      <input type="hidden" name="contact_id" value="<?= $row['id'] ?>">
                      <button type="submit" class="edit-btn">Reply</button>
                    </form>
                    <form method="get" action="approve_contact.php">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <button type="submit" class="approve-btn">Approve</button>
                    </form>
                    <form method="post" action="delete.php" onsubmit="return confirm('Are you sure?');">
                      <input type="hidden" name="type" value="contact">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?><p>No contact/booking requests yet.</p><?php endif; ?>
      </div>
    </div>

    <!-- Approved Appointments -->
    <div class="dashboard-section">
      <h3 class="section-header">📋 Approved Appointments</h3>
      <div class="dashboard-section-content">
        <p><a href="add_appointment.php" class="button-link">+ Add New Appointment</a></p>
        <?php if (count($appointments) > 0): ?>
          <table class="admin-table">
            <thead>
              <tr>
                <th class="col-name">Name</th>
                <th class="col-email">Email</th>
                <th class="col-service">Service</th>
                <th class="col-date">Date</th>
                <th class="col-time">Time</th>
                <th class="col-artist">Artist</th>
                <th class="col-status">Status</th>
                <th class="col-actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $appt): ?>
                <tr>
                  <td class="col-name"><?= htmlspecialchars($appt['client_name']) ?></td>
                  <td class="col-email"><?= htmlspecialchars($appt['client_email']) ?></td>
                  <td class="col-service"><?= htmlspecialchars($appt['service_type']) ?></td>
                  <td class="col-date"><?= htmlspecialchars($appt['appointment_date']) ?></td>
                  <td class="col-time"><?= t12($appt['appointment_time']) ?></td>
                  <td class="col-artist"><?= htmlspecialchars($appt['artist_name'] ?? '—') ?></td>
                  <td class="col-status status-cell">
                    <span class="status-label approved">Approved</span>
                  </td>
                  <td class="col-actions action-buttons">
                    <form method="get" action="edit_appointment.php">
                      <input type="hidden" name="id" value="<?= $appt['id'] ?>">
                      <button type="submit" class="edit-btn">Edit</button>
                    </form>
                    <form method="post" action="delete.php" onsubmit="return confirm('Delete this appointment?');">
                      <input type="hidden" name="type" value="appointment">
                      <input type="hidden" name="id" value="<?= $appt['id'] ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?><p>No approved appointments yet.</p><?php endif; ?>
      </div>
    </div>

    <!-- Users -->
    <div class="dashboard-section">
      <h3 class="section-header">👥 Registered Users</h3>
      <div class="dashboard-section-content">
        <?php if (count($users) > 0): ?>
          <table class="admin-table">
            <thead>
              <tr>
                <th class="col-name">Full Name</th>
                <th class="col-username">Username</th>
                <th class="col-email">Email</th>
                <th class="col-role">Role</th>
                <th class="col-actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td class="col-name"><?= htmlspecialchars($user['full_name']) ?></td>
                  <td class="col-username"><?= htmlspecialchars($user['username']) ?></td>
                  <td class="col-email"><?= htmlspecialchars($user['email']) ?></td>
                  <td class="col-role"><?= htmlspecialchars($user['role']) ?></td>
                  <td class="col-actions action-buttons">
                    <form method="post" action="delete.php" onsubmit="return confirm('Are you sure?');">
                      <input type="hidden" name="type" value="user">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
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
          <table class="admin-table">
            <thead>
              <tr>
                <th class="col-image">Image</th>
                <th class="col-name">Name</th>
                <th class="col-specialty">Specialty</th>
                <th class="col-bio">Bio</th>
                <th class="col-actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($artists as $a): ?>
                <tr>
                  <td class="col-image">
                    <?= $a['profile_image'] ? '<img src="images/artists/' . htmlspecialchars($a['profile_image']) . '" alt="Artist Image" style="height:80px;border-radius:5px;">' : '—' ?>
                  </td>
                  <td class="col-name"><?= htmlspecialchars($a['name']) ?></td>
                  <td class="col-specialty"><?= htmlspecialchars($a['specialty']) ?></td>
                  <td class="col-bio"><?= nl2br(htmlspecialchars($a['bio'])) ?></td>
                  <td class="col-actions action-buttons">
                    <form method="get" action="edit_artist.php">
                      <input type="hidden" name="id" value="<?= $a['id'] ?>">
                      <button type="submit" class="edit-btn">Edit</button>
                    </form>
                    <form method="post" action="delete.php" onsubmit="return confirm('Delete this artist?');">
                      <input type="hidden" name="type" value="artist">
                      <input type="hidden" name="id" value="<?= $a['id'] ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
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
          <table class="admin-table">
            <thead>
              <tr>
                <th class="col-image">Image</th>
                <th class="col-title">Title</th>
                <th class="col-description">Description</th>
                <th class="col-category">Category</th>
                <th class="col-artist">Artist</th>
                <th class="col-actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($gallery as $img): ?>
                <tr>
                  <td class="col-image"><img src="images/gallery/<?= htmlspecialchars($img['image_path']) ?>" alt="Gallery Image" style="height:80px;"></td>
                  <td class="col-title"><?= htmlspecialchars($img['title']) ?></td>
                  <td class="col-description"><?= htmlspecialchars($img['description']) ?></td>
                  <td class="col-category"><?= htmlspecialchars($img['category']) ?: '—' ?></td>
                  <td class="col-artist"><?= htmlspecialchars($img['artist_name'] ?? '—') ?></td>
                  <td class="col-actions action-buttons">
                    <form method="get" action="edit_gallery.php">
                      <input type="hidden" name="id" value="<?= $img['id'] ?>">
                      <button type="submit" class="edit-btn">Edit</button>
                    </form>
                    <form method="post" action="delete.php" onsubmit="return confirm('Delete this image?');">
                      <input type="hidden" name="type" value="gallery">
                      <input type="hidden" name="id" value="<?= $img['id'] ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?><p>No images uploaded yet.</p><?php endif; ?>
      </div>
    </div>

    <!-- Flash Tattoos -->
    <div class="dashboard-section">
      <h3 class="section-header">⚡ Flash Tattoos</h3>
      <div class="dashboard-section-content">
        <p><a href="upload_flash_tattoo_form.php" class="button-link">+ Upload New Flash Tattoo</a></p>
        <?php
          $flash = $pdo->query("SELECT * FROM flash_tattoos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php if (count($flash) > 0): ?>
          <table class="admin-table">
            <thead>
              <tr>
                <th class="col-image">Image</th>
                <th class="col-title">Title</th>
                <th class="col-price">Price</th>
                <th class="col-actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($flash as $f): ?>
                <tr>
                  <td class="col-image">
                    <?php if (!empty($f['image_path'])): ?>
                      <img src="<?= htmlspecialchars($f['image_path']) ?>" alt="Flash Tattoo" style="height:80px;">
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                  <td class="col-title"><?= htmlspecialchars($f['title']) ?></td>
                  <td class="col-price"><?= htmlspecialchars($f['price']) ?></td>
                  <td class="col-actions action-buttons">
                    <form method="get" action="edit_flash_tattoo.php">
                      <input type="hidden" name="id" value="<?= $f['id'] ?>">
                      <button type="submit" class="edit-btn">Edit</button>
                    </form>
                    <form method="post" action="delete.php" onsubmit="return confirm('Delete this flash tattoo?');">
                      <input type="hidden" name="type" value="flash_tattoo">
                      <input type="hidden" name="id" value="<?= $f['id'] ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?><p>No flash tattoos uploaded yet.</p><?php endif; ?>
      </div>
    </div>

    <!-- Logout -->
    <div style="text-align:center; margin-top: 40px;">
      <a href="logout.php" class="button-link">Logout</a>
    </div>
  </div> <!-- Close .dashboard-container -->
</main>

<?php include("footer.php"); ?>

</body>
</html>