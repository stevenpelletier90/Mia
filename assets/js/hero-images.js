/**
 * Hero Images Lazy Loading Override
 * Forces immediate loading of hero images to prevent lazy loading plugin interference
 */

document.addEventListener('DOMContentLoaded', function() {
    const heroImages = document.querySelectorAll('.mia-hero-image');
    
    heroImages.forEach(function(img) {
        // Force immediate loading by moving data attributes to actual attributes
        if (img.dataset.lazySrc) {
            img.src = img.dataset.lazySrc;
            img.removeAttribute('data-lazy-src');
        }
        
        if (img.dataset.lazySrcset) {
            img.srcset = img.dataset.lazySrcset;
            img.removeAttribute('data-lazy-srcset');
        }
        
        if (img.dataset.lazySizes) {
            img.sizes = img.dataset.lazySizes;
            img.removeAttribute('data-lazy-sizes');
        }
        
        // Remove lazy loading classes and add loaded class
        img.classList.remove('lazy', 'lazyload', 'lazyloading');
        img.classList.add('lazyloaded');
    });
});