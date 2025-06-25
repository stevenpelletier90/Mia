<?php
/**
 * Mia Aesthetics Schema Integration with Yoast SEO
 * ------------------------------------------------
 * Main schema loader - uses organized class structure
 * 
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load and initialize the schema system
require_once get_template_directory() . '/inc/schema/class-schema-loader.php';

use Mia_Aesthetics\Schema\Schema_Loader;

// Initialize schema on theme setup
add_action( 'after_setup_theme', function() {
    Schema_Loader::init();
} );