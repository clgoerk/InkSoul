document.addEventListener("DOMContentLoaded", function () {
  // === Hamburger Menu Toggle ===
  const hamburger = document.getElementById("mobileMenu");
  const navList = document.querySelector(".nav-links");

  if (hamburger && navList) {
    hamburger.addEventListener("click", function () {
      // Toggle the hamburger icon animation and nav visibility
      hamburger.classList.toggle("open");
      navList.classList.toggle("show");
    });
  }

  // === Collapse Dashboard Sections by Default ===
  const sectionHeaders = document.querySelectorAll(".dashboard-section h3");

  sectionHeaders.forEach(header => {
    const content = header.nextElementSibling;

    if (content) {
      // Start collapsed
      content.style.display = "none";

      // Make header clickable
      header.classList.add("collapsible");
      header.style.cursor = "pointer";

      // Toggle section on header click
      header.addEventListener("click", () => {
        content.style.display = (content.style.display === "none") ? "block" : "none";
      });
    }
  });

  // === Flash Sale Scroll Buttons ===
  const grid = document.querySelector(".flash-sale-grid");
  const leftBtn = document.querySelector(".scroll-btn.left");
  const rightBtn = document.querySelector(".scroll-btn.right");

  if (grid && leftBtn && rightBtn) {
    // Scroll left by 300px
    leftBtn.addEventListener("click", () => {
      grid.scrollBy({ left: -300, behavior: "smooth" });
    });

    // Scroll right by 300px
    rightBtn.addEventListener("click", () => {
      grid.scrollBy({ left: 300, behavior: "smooth" });
    });
  }

  // === Animated "Our Process" Section (Stacked Steps) ===
  const processDisplay = document.getElementById('processDisplay');
  if (processDisplay) {
    const steps = [
      '1. <strong>Consultation:</strong> Share your ideas and get artist feedback.',
      '2. <strong>Design:</strong> We craft a custom piece just for you.',
      '3. <strong>Booking:</strong> Secure your session with your chosen artist.',
      '4. <strong>Tattoo Session:</strong> Relax and let the artistry happen.',
      '5. <strong>Aftercare:</strong> Follow our guide to heal your new tattoo.'
    ];

    let currentStep = 0;

    // Show steps one by one, stacked with animation
    function showNextStep() {
      if (currentStep >= steps.length) return;

      // Create new step element
      const stepDiv = document.createElement('div');
      stepDiv.className = 'process-step';
      stepDiv.innerHTML = steps[currentStep];

      // Append to display container
      processDisplay.appendChild(stepDiv);

      // Trigger entry animation
      setTimeout(() => {
        stepDiv.classList.add('show');
      }, 50);

      // Prepare for next step
      currentStep++;
      setTimeout(showNextStep, 3000);
    }

    showNextStep();
  }
});


// === Load Artist Profile + Tattoo Gallery ===
function loadArtist(id) {
  fetch('artist_details.php?id=' + id)
    .then(response => response.json())
    .then(data => {
      const profile = data.artist;
      const tattoos = data.tattoos;

      // === Build Artist Profile HTML ===
      const profileHTML = `
        <div class="artist-profile-content">
          <img class="artist-photo" src="images/artists/${profile.profile_image}" alt="${profile.name}">
          <h2>${profile.name}</h2>
          <p class="artist-bio">${profile.bio}</p>
          <div class="artist-meta"><strong>Specialty:</strong> ${profile.specialty}</div>
          <div class="book-appointment">
            <a href="contact.php?artist_id=${profile.id}" class="button-link">Book Appointment</a>
          </div>
        </div>
      `;
      document.getElementById('artistProfile').innerHTML = profileHTML;

      // === Build Tattoo Gallery HTML ===
      const tattooHTML = tattoos.map(t => `
        <a href="images/gallery/${t.image_path}" data-lightbox="artist-gallery" data-title="${t.title || ''}">
          <img src="images/gallery/${t.image_path}" alt="${t.title || ''}" class="tattoo-thumb">
        </a>
      `).join('');
      document.getElementById('tattooGallery').innerHTML = tattooHTML;

      // === Reveal artist detail section and scroll to it ===
      const detailSection = document.getElementById('artistDetail');
      detailSection.style.display = 'block';
      detailSection.scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
      console.error('Error loading artist data:', error);
    });
}