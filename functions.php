<?php
/**
 * @package Mia_Aesthetics
 */

add_theme_support( 'post-thumbnails' );

// Include helper files
require_once get_template_directory() . '/inc/state-abbreviations.php';
require_once get_template_directory() . '/inc/disable-comments.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/schema.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/queries.php';
require_once get_template_directory() . '/inc/media-helpers.php';
require_once get_template_directory() . '/inc/menu-helpers.php';
require_once get_template_directory() . '/inc/template-helpers.php';
require_once get_template_directory() . '/inc/cache-helpers.php';
?>
