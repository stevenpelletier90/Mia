/**
 * Pardot Website Tracking Script
 * Optimized for performance and WordPress integration
 */

// Set Pardot configuration from WordPress
if (typeof miaPardotConfig !== 'undefined') {
    window.piAId = miaPardotConfig.piAId;
    window.piCId = miaPardotConfig.piCId;
}

// Asynchronous loading function
function loadPardotTracking() {
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.async = true;
    script.src = (location.protocol === 'https:' ? 'https://pi' : 'https://cdn') + '.pardot.com/pd.js';
    
    const firstScript = document.getElementsByTagName('script')[0];
    firstScript.parentNode.insertBefore(script, firstScript);
}

// Load when DOM is ready and page is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadPardotTracking);
} else {
    // DOM already loaded
    if (document.readyState === 'complete') {
        loadPardotTracking();
    } else {
        window.addEventListener('load', loadPardotTracking);
    }
}