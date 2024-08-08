<?php

// Queue parent style followed by child/customized style
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 999);

function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
}


// Replacement for prefers_os_menu() found in dhara theme functions.php JCH 7/22/24
function prefers_os_menu2($wp) {
    $current_url = home_url(add_query_arg([], $wp->request));
    if (preg_match("|/os|i", $current_url)) {
        return true;
    } else if (preg_match("|/category|i", $current_url)) {
        $current_announcements_ID = 9;
        $old_announcements_ID = 10;
        $prefers_ns_categories = [$current_announcements_ID, $old_announcements_ID];
        // JJD 8/18/23 #6 handle null object
        $cat_ID = get_queried_object()->term_id ?? 'dummy';
        return !in_array($cat_ID, $prefers_ns_categories);
    } else if (is_single()) {
        return true;
    } else if (is_search()) {
        return true;
    } else if (is_restricted()) {
        return true;
    }
    return false;
}
