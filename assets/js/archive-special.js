/**
 * specials-archive.js
 * --------------------------------------------------------------------
 * Initialises the Slick testimonial slider on the Specials archive
 * template (archive-special.php).  Loaded conditionally when the
 * archive for the custom post type “special” is displayed.
 *
 * Depends on:
 * • jQuery
 * • Slick slider (slick.min.js) already enqueued separately
 *
 * @package Mia_Aesthetics
 */

(function ($) {
  "use strict";

  $(function () {
    if (typeof $.fn.slick !== "function") return;

    $(".testimonial-slider").slick({
      dots: true,
      arrows: false,
      infinite: true,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });
  });
})(jQuery);
