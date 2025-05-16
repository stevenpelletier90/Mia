/**
 * JavaScript specific to the "Surgeon" single template
 */

// Modern Scrollspy Implementation using Intersection Observer API

document.addEventListener("DOMContentLoaded", function () {
  // Disable any Bootstrap scrollspy
  disableBootstrapScrollspy();

  // Set up Intersection Observer for scrollspy
  setupScrollspy();

  // Set up smooth scrolling for the navigation buttons
  setupSmoothScrolling();
});

/**
 * Disables any Bootstrap scrollspy that might be active
 */
function disableBootstrapScrollspy() {
  const bodyElement = document.body;

  // Remove any Bootstrap scrollspy instance if it exists
  if (window.bootstrap && bootstrap.ScrollSpy) {
    try {
      const instance = bootstrap.ScrollSpy.getInstance(bodyElement);
      if (instance) {
        instance.dispose();
      }
    } catch (error) {
      console.warn("Could not dispose Bootstrap ScrollSpy:", error);
    }
  }

  // Remove scrollspy attributes from body
  bodyElement.removeAttribute("data-bs-spy");
  bodyElement.removeAttribute("data-bs-target");
  bodyElement.removeAttribute("data-bs-offset");
}

/**
 * Sets up the Intersection Observer for scrollspy
 */
function setupScrollspy() {
  // Get all sections we want to observe
  const aboutSection = document.querySelector("#surgeon-about");
  const specialitiesSection = document.querySelector("#surgeon-specialities");
  const beforeAfterSection = document.querySelector("#surgeon-before-after");

  // Get video container - either desktop or mobile version
  const desktopVideoContainer = document.querySelector(".d-none.d-lg-block .video-container");
  const mobileVideoContainer = document.querySelector(".d-lg-none .video-container");

  // Create an array of sections to observe in the order they appear on the page
  const sectionsToObserve = [];

  // Add video container first if it exists - it should be highlighted by About nav button
  if (mobileVideoContainer || desktopVideoContainer) {
    sectionsToObserve.push({
      element: window.innerWidth >= 992 ? desktopVideoContainer : mobileVideoContainer,
      navButton: document.querySelector('.surgeon-nav-btn[href="#surgeon-about"]'),
    });
  }

  // Add other sections
  if (aboutSection) {
    sectionsToObserve.push({
      element: aboutSection,
      navButton: document.querySelector('.surgeon-nav-btn[href="#surgeon-about"]'),
    });
  }

  if (specialitiesSection) {
    sectionsToObserve.push({
      element: specialitiesSection,
      navButton: document.querySelector('.surgeon-nav-btn[href="#surgeon-specialities"]'),
    });
  }

  if (beforeAfterSection) {
    sectionsToObserve.push({
      element: beforeAfterSection,
      navButton: document.querySelector('.surgeon-nav-btn[href="#surgeon-before-after"]'),
    });
  }

  // Filter out any entries where element is null
  const validSections = sectionsToObserve.filter((section) => section.element !== null);

  // Intersection Observer options
  // Adjust rootMargin to match navbar height - intersections will be calculated relative to this
  const navbarHeight = getNavbarHeight();
  const options = {
    root: null, // using viewport
    rootMargin: `-${navbarHeight}px 0px -50% 0px`, // negative top margin to account for navbar, bottom is 50% of viewport
    threshold: 0, // trigger as soon as element becomes visible
  };

  // Create an observer that will highlight the active nav link
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      // Find the section in our array
      const section = validSections.find((s) => s.element === entry.target);
      if (!section) return;

      if (entry.isIntersecting) {
        // This section is now visible, set its nav button as active
        setActiveNavButton(section.navButton);
      }
    });
  }, options);

  // Start observing each section
  validSections.forEach((section) => {
    observer.observe(section.element);
  });

  // Set the first section as active by default
  if (validSections.length > 0) {
    setActiveNavButton(validSections[0].navButton);
  }
}

/**
 * Sets the specified nav button as the active one
 */
function setActiveNavButton(activeButton) {
  if (!activeButton) return;

  // Remove active class from all nav buttons
  document.querySelectorAll(".surgeon-nav-btn").forEach((btn) => {
    btn.classList.remove("active");
  });

  // Add active class to the current nav button
  activeButton.classList.add("active");
}

/**
 * Sets up smooth scrolling for navigation buttons
 */
function setupSmoothScrolling() {
  document.querySelectorAll(".surgeon-nav-btn").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      // Get the target section ID
      const targetId = this.getAttribute("href");

      // Handle special case for About button (should scroll to video if available)
      if (targetId === "#surgeon-about") {
        const videoContainer =
          window.innerWidth >= 992
            ? document.querySelector(".d-none.d-lg-block .video-container")
            : document.querySelector(".d-lg-none .video-container");

        if (videoContainer) {
          scrollToElement(videoContainer);
          return;
        }
      }

      // For other buttons, scroll to the corresponding section
      const targetSection = document.querySelector(targetId);
      if (targetSection) {
        scrollToElement(targetSection);
      }
    });
  });
}

/**
 * Scrolls the page to make the specified element visible
 */
function scrollToElement(element) {
  if (!element) return;

  const navbarHeight = getNavbarHeight();
  // Add extra offset (10px) so the section is slightly below the nav
  const extraOffset = 10;
  const elementPosition = element.getBoundingClientRect().top + window.scrollY - navbarHeight - extraOffset;

  window.scrollTo({
    top: elementPosition,
    behavior: "smooth",
  });
}

/**
 * Calculates the height of the navbar (header + mobile nav if visible)
 */
function getNavbarHeight() {
  const header = document.querySelector("header");
  const mobileNav = document.querySelector(".surgeon-mobile-nav");

  const headerHeight = header ? header.offsetHeight : 0;
  const mobileNavHeight = mobileNav && window.innerWidth < 768 ? mobileNav.offsetHeight : 0;

  return headerHeight + mobileNavHeight;
}

// (Removed dynamic hero height logic)
