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
          <table>
            <thead>
              <tr>
                <th>Name</th><th>Email</th><th>Service</th><th>Artist</th><th>Date</th><th>Time</th><th>Message</th><th>Status</th><th>Submitted</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($contacts as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['service_type']) ?></td>
                  <td><?= htmlspecialchars($row['artist_name'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($row['preferred_date']) ?></td>
                  <td><?= htmlspecialchars($row['preferred_time']) ?></td>
                  <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                  <td class="status-cell">
                    <span class="status-label <?= htmlspecialchars($row['status']) ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                  <td class="action-buttons">
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
          <table>
            <thead>
              <tr>
                <th>Name</th><th>Email</th><th>Service</th><th>Date</th><th>Time</th><th>Artist</th><th>Notes</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $appt): ?>
                <tr>
                  <td><?= htmlspecialchars($appt['client_name']) ?></td>
                  <td><?= htmlspecialchars($appt['client_email']) ?></td>
                  <td><?= htmlspecialchars($appt['service_type']) ?></td>
                  <td><?= htmlspecialchars($appt['appointment_date']) ?></td>
                  <td><?= htmlspecialchars($appt['appointment_time']) ?></td>
                  <td><?= htmlspecialchars($appt['artist_name'] ?? '—') ?></td>
                  <td><?= nl2br(htmlspecialchars($appt['notes'])) ?></td>
                  <td class="action-buttons">
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
          <table>
            <thead>
              <tr>
                <th>Image</th><th>Title</th><th>Description</th><th>Category</th><th>Artist</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($gallery as $img): ?>
                <tr>
                  <td><img src="images/gallery/<?= htmlspecialchars($img['image_path']) ?>" alt="Gallery Image" style="height:80px;"></td>
                  <td><?= htmlspecialchars($img['title']) ?></td>
                  <td><?= htmlspecialchars($img['description']) ?></td>
                  <td><?= htmlspecialchars($img['category']) ?: '—' ?></td>
                  <td><?= htmlspecialchars($img['artist_name'] ?? '—') ?></td>
                  <td class="action-buttons">
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
          <table>
            <thead>
              <tr>
                <th>Image</th><th>Title</th><th>Price</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($flash as $f): ?>
                <tr>
                  <td>
                    <?php if (!empty($f['image_path'])): ?>
                      <img src="<?= htmlspecialchars($f['image_path']) ?>" alt="Flash Tattoo" style="height:80px;">
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($f['title']) ?></td>
                  <td><?= htmlspecialchars($f['price']) ?></td>
                  <td class="action-buttons">
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
  </div> <!-- Close .card-container -->
</main>

<?php include("footer.php"); ?>

</body>
</html>