document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.getElementById("mobileMenu");
  const navList = document.querySelector(".nav-links");

  if (hamburger && navList) {
    hamburger.addEventListener("click", function () {
      hamburger.classList.toggle("open");
      navList.classList.toggle("show");
    });
  }

  // Collapse dashboard sections by default
  const sectionHeaders = document.querySelectorAll(".dashboard-section h3");

  sectionHeaders.forEach(header => {
    const content = header.nextElementSibling;

    if (content) {
      content.style.display = "none";
      header.classList.add("collapsible");
      header.style.cursor = "pointer";

      header.addEventListener("click", () => {
        content.style.display = (content.style.display === "none") ? "block" : "none";
      });
    }
  });

  // Flash Sale scroll buttons
  const grid = document.querySelector(".flash-sale-grid");
  const leftBtn = document.querySelector(".scroll-btn.left");
  const rightBtn = document.querySelector(".scroll-btn.right");

  if (grid && leftBtn && rightBtn) {
    leftBtn.addEventListener("click", () => {
      grid.scrollBy({ left: -300, behavior: "smooth" });
    });
    rightBtn.addEventListener("click", () => {
      grid.scrollBy({ left: 300, behavior: "smooth" });
    });
  }
});

// Artist detail loader
function loadArtist(id) {
  fetch('artist_details.php?id=' + id)
    .then(response => response.json())
    .then(data => {
      const profile = data.artist;
      const tattoos = data.tattoos;

      // Artist Info
      const profileHTML = `
        <div class="artist-profile-content">
          <img class="artist-photo" src="images/artists/${profile.profile_image}" alt="${profile.name}">
          <h2>${profile.name}</h2>
          <p class="artist-bio">${profile.bio}</p>
          <div class="artist-meta">
            <strong>Specialty:</strong> ${profile.specialty}
          </div>
        </div>
      `;
      document.getElementById('artistProfile').innerHTML = profileHTML;

      // Tattoo Images
      const tattooHTML = tattoos.map(t => `
        <img src="images/gallery/${t.image_path}" alt="">
      `).join('');
      document.getElementById('tattooGallery').innerHTML = tattooHTML;

      // Reveal Section
      const detailSection = document.getElementById('artistDetail');
      detailSection.style.display = 'block';
      detailSection.scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
      console.error('Error loading artist data:', error);
    });
}
