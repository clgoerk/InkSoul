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
});
