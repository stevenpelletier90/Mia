<?php
/**
 * Theme bootstrap for Mia Aesthetics.
 *
 * Loads all core theme features and helper modules.
 * Each include is responsible for a specific area of theme functionality.
 */

add_theme_support( 'post-thumbnails' );

// Load helper modules (see inc/ for details)
require_once get_template_directory() . '/inc/state-abbreviations.php'; // US state abbreviation lookup and helper
require_once get_template_directory() . '/inc/disable-comments.php';    // Disables all comment functionality
require_once get_template_directory() . '/inc/enqueue.php';             // Enqueue scripts and styles
require_once get_template_directory() . '/inc/schema.php';              // Schema and structured data output
require_once get_template_directory() . '/inc/menus.php';               // Menu structure and rendering
require_once get_template_directory() . '/inc/queries.php';             // Custom query modifications and filters
require_once get_template_directory() . '/inc/media-helpers.php';       // Image sizes, video helpers, media utilities
require_once get_template_directory() . '/inc/menu-helpers.php';        // Menu rendering helpers and caching
require_once get_template_directory() . '/inc/template-helpers.php';    // Template/UI helpers
require_once get_template_directory() . '/inc/cache-helpers.php';       // Cache management and clearing
require_once get_template_directory() . '/inc/security-headers.php';   // Security headers and protections
?>
