<?php
/**
 * Enqueue styles and scripts for Bookory Child Theme
 */
function bookory_child_enqueue_assets() {
    // Parent + Child styles
    wp_enqueue_style(
        'bookory-parent-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'bookory-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['bookory-parent-style'],
        wp_get_theme()->get('Version')
    );

    // Bootstrap
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        [],
        '5.3.3'
    );

    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        ['jquery'],
        '5.3.3',
        true
    );

    // Child custom script (search toggle etc.)
    wp_enqueue_script(
        'bookory-child-js',
        get_stylesheet_directory_uri() . '/js/script.js',
        ['jquery'],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'bookory_child_enqueue_assets');


/**
 * Add search icon to primary menu
 */
function bookory_add_search_icon_to_menu( $items, $args ) {
    if ( $args->theme_location === 'primary' ) {
        $items .= '<li class="menu-item search-menu-item">
            <a href="#" class="search-toggle">
                <i class="fas fa-search"></i>
            </a>
        </li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'bookory_add_search_icon_to_menu', 10, 2 );


/**
 * Force search to only show blog posts
 */
function bookory_search_only_blogs( $query ) {
    if ( $query->is_search && ! is_admin() ) {
        $query->set( 'post_type', 'post' );
    }
}
add_action( 'pre_get_posts', 'bookory_search_only_blogs' );
