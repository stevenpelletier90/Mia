/**
 * JavaScript specific to the “Surgeon” single template
 * (extracted from inline script in single-surgeon.php)
 */

// Function to calculate and set the hero section height
function setSurgeonHeroHeight() {
  // Get the elements
  const header = document.querySelector("header");
  const breadcrumbs = document.getElementById("breadcrumbs");
  const mobileCta = document.querySelector(".mobile-cta-container");
  const mobileNav = document.querySelector(".surgeon-mobile-nav");
  const surgeonHero = document.querySelector(".surgeon-hero-section");

  if (!surgeonHero) return;

  // Check if we're on mobile (max-width: 767px)
  if (window.innerWidth <= 767) {
    // Get the heights
    const headerHeight = header ? header.offsetHeight : 0;
    const breadcrumbsHeight = breadcrumbs ? breadcrumbs.offsetHeight : 0;
    const mobileCtaHeight = mobileCta ? mobileCta.offsetHeight : 0;
    const mobileNavHeight = mobileNav ? mobileNav.offsetHeight : 0;

    // Calculate the total height to subtract
    const subtractHeight = headerHeight + breadcrumbsHeight + mobileCtaHeight + mobileNavHeight;

    // Get the actual viewport height (works better on iOS)
    const windowHeight = window.innerHeight;

    // Set the hero height with a minimum to prevent it from becoming too small
    const calculatedHeight = windowHeight - subtractHeight;
    const minHeight = 250; // Minimum height in pixels

    // Use the larger of the calculated height or minimum height
    surgeonHero.style.height = `${Math.max(calculatedHeight, minHeight)}px`;
  } else {
    // On desktop, use the CSS default (350px)
    surgeonHero.style.height = "";
  }
}

// Function to handle active state of navigation buttons
function handleNavActiveState() {
  const navBtns = document.querySelectorAll(".surgeon-nav-btn");
  const sections = Array.from(navBtns).map((btn) => {
    const targetId = btn.getAttribute("href").substring(1);
    return document.getElementById(targetId);
  });

  if (navBtns.length === 0 || sections.some((section) => !section)) return;

  // Get current scroll position
  const scrollPosition = window.scrollY;

  // Find which section is currently in view
  let activeIndex = 0;
  sections.forEach((section, index) => {
    if (!section) return;

    const sectionTop = section.offsetTop;
    const sectionHeight = section.offsetHeight;

    if (scrollPosition >= sectionTop - 200 && scrollPosition < sectionTop + sectionHeight - 200) {
      activeIndex = index;
    }
  });

  // Update active class
  navBtns.forEach((btn, index) => {
    if (index === activeIndex) {
      btn.classList.add("active");
    } else {
      btn.classList.remove("active");
    }
  });
}

// Run on page load
document.addEventListener("DOMContentLoaded", function () {
  setSurgeonHeroHeight();

  // Add click event listeners to nav buttons for smooth scrolling
  document.querySelectorAll(".surgeon-nav-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");
      const targetElement = document.querySelector(targetId);

      if (targetElement) {
        const headerHeight = document.querySelector("header").offsetHeight;
        const mobileNavHeight = document.querySelector(".surgeon-mobile-nav").offsetHeight;
        const offset = headerHeight + mobileNavHeight;

        const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - offset;

        window.scrollTo({
          top: targetPosition,
          behavior: "smooth",
        });
      }
    });
  });
});

// Run on window resize
window.addEventListener("resize", setSurgeonHeroHeight);

// Run on scroll
window.addEventListener("scroll", handleNavActiveState);

// Run after a short delay to ensure all elements are fully rendered
setTimeout(function () {
  setSurgeonHeroHeight();
  handleNavActiveState();
}, 500);
