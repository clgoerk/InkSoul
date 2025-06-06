<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact & Booking - Ink Soul</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

  <?php include("header.php"); ?>

  <main class="home-content">
    <h2 style="text-align:center;">Contact Us & Book an Appointment</h2>

    <form method="POST" action="contact_controller.php" class="contact-form" autocomplete="off">
      <label for="name">Full Name</label>
      <input type="text" name="name" id="name" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="service_type">Service Type</label>
      <select name="service_type" id="service_type" required>
        <option value="">-- Please choose an option --</option>
        <option value="Tattoo">Tattoo</option>
        <option value="Piercing">Piercing</option>
        <option value="Consultation">Consultation</option>
      </select>

      <label for="preferred_date">Preferred Date</label>
      <input type="date" name="preferred_date" id="preferred_date" required>

      <label for="preferred_time">Preferred Time</label>
      <input type="time" name="preferred_time" id="preferred_time" required>

      <label for="message">Message</label>
      <textarea name="message" id="message" rows="5" required></textarea>

      <input type="submit" value="Submit Request">
    </form>
  </main>

  <?php include("footer.php"); ?>

</body>
</html>