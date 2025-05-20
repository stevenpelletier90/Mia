/**
 * Home page specific JavaScript
 * Animates the statistics numbers so they count up
 * when the element scrolls into the viewport.
 *
 * Relies on:
 * - IntersectionObserver (widely supported in modern browsers)
 * - No external libraries needed
 */

document.addEventListener("DOMContentLoaded", function () {
  const counters = document.querySelectorAll(".stats-number");
  if (!counters.length) {
    return; // Nothing to do
  }

  /**
   * Count-up animation for a single element.
   * @param {HTMLElement} el .stats-number element (holds data-count)
   */
  function animateCount(el) {
    const target = parseInt(el.dataset.count, 10);
    if (isNaN(target)) {
      return;
    }

    const span = el.querySelector("span");
    if (!span) {
      return;
    }

    // Get the original text to check for special formatting
    const originalText = span.textContent;
    const hasPlus = originalText.includes("+");
    const isYear = target >= 1900 && target <= 2100; // Likely a year if between 1900-2100

    const duration = 1200; // total animation time in ms
    const startTime = performance.now();

    function update(now) {
      const progress = Math.min((now - startTime) / duration, 1); // 0‒1
      const current = Math.floor(progress * target);

      // Format the number based on special cases
      if (isYear) {
        // Don't use toLocaleString for years to avoid commas
        span.textContent = current.toString();
      } else {
        // Use toLocaleString for other numbers to add commas
        span.textContent = current.toLocaleString();
        // Add + sign if the original had it
        if (hasPlus) {
          span.textContent += "+";
        }
      }

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        // Set the final value with appropriate formatting
        if (isYear) {
          span.textContent = target.toString();
        } else {
          span.textContent = target.toLocaleString();
          if (hasPlus) {
            span.textContent += "+";
          }
        }
      }
    }

    requestAnimationFrame(update);
  }

  // Only trigger once per element
  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          obs.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.4, // element is ~40 % visible
    }
  );

  counters.forEach((counter) => observer.observe(counter));
});
