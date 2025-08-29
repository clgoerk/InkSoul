<?php
  $pageTitle = "Our Services";
  include 'header.php';
?>
<link rel="stylesheet" href="css/main.css">

<main class="services-container">

  <!-- Services Grid Card -->
  <section class="card">
    <h2 class="section-title">What We Offer</h2>
    <div class="services-grid">
      <div class="service-card">
        <img src="images/custom1.jpg" alt="Custom Tattoos">
        <h3>Custom Tattoos</h3>
        <p>Unique, one-of-a-kind tattoos crafted with your vision in mind. Collaborate with our artists for a truly personal design.</p>
      </div>

      <div class="service-card">
        <img src="images/coverup1.jpg" alt="Cover-Up Tattoos">
        <h3>Cover-Up Tattoos</h3>
        <p>Transform old or unwanted tattoos into stunning new pieces that reflect who you are today.</p>
      </div>

      <div class="service-card">
        <img src="images/consult1.jpg" alt="Consultations">
        <h3>Consultations</h3>
        <p>Meet with our experienced artists to plan your tattoo, discuss ideas, and get expert guidance before you commit.</p>
      </div>

      <div class="service-card">
        <img src="images/aftercare1.jpg" alt="Tattoo Aftercare">
        <h3>Aftercare Guidance</h3>
        <p>Detailed aftercare instructions and support to ensure your tattoo heals beautifully and lasts a lifetime.</p>
      </div>
    </div>
  </section>

  <!-- How It Works Card -->
<section class="card">
  <h2 class="section-title">Our Process</h2>
  <div class="animated-process">
    <div id="processDisplay"></div>
  </div>
</section>

  <!-- CTA Card -->
  <section class="card text-center">
    <h2 class="section-title">Ready to Get Inked?</h2>
    <a href="contact.php" class="button-link">Book a Consultation</a>
  </section>

</main>

<?php include 'footer.php'; ?>