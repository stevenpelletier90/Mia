<?php
/**
 * Completely disables all comment functionality in WordPress.
 *
 * Removes comment support from all post types, disables comment UI in admin,
 * removes comment REST endpoints, and redirects comment admin pages.
 */
function mia_disable_comments() {
    // Disable comments and pings on all post types
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Remove comments page from admin menu
    add_action('admin_menu', function() {
        remove_menu_page('edit-comments.php');
    });

    // Remove comments from admin bar
    add_action('wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });

    // Remove comment support from all post types
    add_action('admin_init', function() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    });

    // Redirect any direct access to the comments admin page
    add_action('admin_init', function() {
        global $pagenow;
        if ($pagenow === 'edit-comments.php' && !defined('DOING_AJAX') && !wp_doing_ajax()) {
            wp_redirect(admin_url());
            exit;
        }
    });

    // Remove recent comments metabox from dashboard
    add_action('admin_init', function() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    });

    // Remove comment endpoints from REST API
    add_filter('rest_endpoints', function($endpoints) {
        if (isset($endpoints['/wp/v2/comments'])) {
            unset($endpoints['/wp/v2/comments']);
        }
        if (isset($endpoints['/wp/v2/comments/(?P<id>[\\d]+)'])) {
            unset($endpoints['/wp/v2/comments/(?P<id>[\\d]+)']);
        }
        return $endpoints;
    });
}
add_action('init', 'mia_disable_comments');
?>
