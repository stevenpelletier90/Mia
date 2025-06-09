<?php
/**
 * Minimal Translation Fix for WordPress 6.7.0+
 * 
 * Suppresses translation loading warnings for English-only sites on WP Engine
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Suppress all translation loading notices
 * Since this is an English-only site on WP Engine, we don't need translations
 */
function mia_suppress_all_textdomain_notices() {
    // Disable the _load_textdomain_just_in_time notices
    add_filter('doing_it_wrong_trigger_error', function($trigger, $function_name) {
        if ($function_name === '_load_textdomain_just_in_time') {
            return false;
        }
        return $trigger;
    }, 10, 2);
}
add_action('init', 'mia_suppress_all_textdomain_notices', 1);
