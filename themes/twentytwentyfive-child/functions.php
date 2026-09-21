<?php
/**
 * Twenty Twenty-Five Child Theme functions and definitions
 *
 * @package Twenty_Twenty_Five_Child
 */

/**
 * Theme setup
 */
function twentytwentyfive_child_setup() {
    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => __('Primary Menu', 'twentytwentyfive-child'),
            'mobile'  => __('Mobile Menu', 'twentytwentyfive-child'),
        )
    );
    
    // Enable post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add custom image size
    add_image_size('sidebar-featured', 400, 300, true);
    
    // Load translation files
    load_theme_textdomain('twentytwentyfive-child', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'twentytwentyfive_child_setup');

/**
 * Remove the legacy image-credit paragraph left in some imported posts.
 *
 * Only a paragraph whose complete normalized text is this exact credit is
 * removed; other captions and post content remain unchanged.
 */
function twentytwentyfive_child_remove_legacy_image_credit($content) {
    if (stripos($content, 'Sampson Construction') === false) {
        return $content;
    }

    return preg_replace_callback(
        '/<p\b[^>]*>.*?<\/p>/is',
        static function ($matches) {
            $text = html_entity_decode(
                wp_strip_all_tags($matches[0]),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );
            $text = str_replace("\xC2\xA0", ' ', $text);
            $text = preg_replace('/\s+/u', ' ', trim($text));

            if (preg_match('/^\x{00A9}\s*2012\s+Sampson\s+Construction$/iu', $text)) {
                return '';
            }

            return $matches[0];
        },
        $content
    );
}
add_filter('the_content', 'twentytwentyfive_child_remove_legacy_image_credit', 20);

/**
 * Return the uploaded default cover image used when a post has no usable image.
 *
 * The migration script stores the SHA-256 as attachment metadata, so this
 * continues to work if WordPress changes the upload year/month URL later.
 */
if (!function_exists('twentytwentyfive_child_default_image_url')) {
    function twentytwentyfive_child_default_image_url() {
        static $default_image_url = null;

        if (null !== $default_image_url) {
            return $default_image_url;
        }

        $default_hash = '86fc7e78bd4b112a58d70e152c0f2dfb6734c2ca7749cb7b2bff29f0df604f5e';
        $attachments = get_posts(array(
            'post_type'        => 'attachment',
            'post_status'      => 'inherit',
            'posts_per_page'   => 1,
            'fields'           => 'ids',
            'meta_key'         => '_anthempress_migration_sha256',
            'meta_value'       => $default_hash,
            'suppress_filters' => true,
        ));

        if (!empty($attachments)) {
            $default_image_url = wp_get_attachment_image_url((int) $attachments[0], 'full');
        }

        if (!$default_image_url) {
            $uploads = wp_upload_dir();
            $default_image_url = trailingslashit($uploads['baseurl']) . '2026/09/Anthempress_cover_Image.png';
        }

        return $default_image_url;
    }
}

/**
 * Enqueue scripts and styles
 */

function twentytwentyfive_child_enqueue_assets() {
    // Parent theme stylesheet
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css'
    );

    // Child theme stylesheet with versioning for cache busting
    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_uri(),
        ['twentytwentyfive-style'],
        filemtime(get_stylesheet_directory() . '/style.css') // cache busting
    );

    // Font Awesome (v6.4.0)
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );
}
add_action('wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_assets');


/**
 * Custom header implementation
 */
function twentytwentyfive_child_custom_header() {
    ?>
    <header class="custom-header">
        <div class="header-top">
            <div class="logo-container">
                <a href="https://anthempress.com/" rel="home">
                    <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/AnthemFulllogo.png" alt="<?php bloginfo('name'); ?>">
                </a>
            </div>

            <div class="header-actions">
                <div class="social-icons">
                    <a href="https://twitter.com/AnthemPress" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                        <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/twitter-x.png" alt="Twitter" width="30" height="30">
                    </a>
                    <a href="https://www.instagram.com/anthem.press/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/instagram_icon.png" alt="Instagram" width="30" height="30">
                    </a>
                    <a href="https://linkedin.com/company/anthem-press" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                        <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/linkedin_icon.png" alt="LinkedIn" width="35" height="35">
                    </a>
                    <a href="https://bsky.app/profile/anthempress.bsky.social" target="_blank" rel="noopener noreferrer" aria-label="Bluesky">
                        <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/sky.png" alt="Bluesky" width="35" height="35">
                    </a>
                    <a href="https://www.facebook.com/Anthem-Press-186030824772461/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/facebook.png" alt="Facebook" width="35" height="35">
                    </a>
                </div>
            </div>
        </div>

    <!-- Mobile Top Bar (Hamburger + Search) -->
    <div class="mobile-top-bar">
        <!-- Hamburger -->
        <div class="hamburger-container">
            <input type="checkbox" id="menu-toggle" class="menu-toggle" />
            <label for="menu-toggle" class="hamburger-btn open-btn">☰</label>
            <label for="menu-toggle" class="hamburger-btn close-btn">✕</label>
        </div>
        
        <!-- Search -->
        <div class="nav-search-inline">
            <a href="#" class="nav-search-link" id="toggle-search">
                <span>SEARCH</span>
		<img src="https://blog.anthempress.com/wp-content/uploads/2025/08/search.png" alt="Search Icon" />
            </a>
        </div>
    </div>
        <!-- Navigation -->
        <div class="nav-wrapper">
            <div class="nav-container">
                <nav class="primary-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false
                    ));
                    ?>
                </nav>

                <!-- Search Inline Trigger -->
                <div class="nav-search-inline-desktop">
                    <a href="#" class="nav-search-link" id="toggle-search" role="button">
                        <span>SEARCH</span>
                        <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/search.png" alt="Search Icon" />
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Bar Outside Header -->
    <div id="nav-search-bar" class="nav-search-bar">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" onsubmit="return validateNavSearch()">
            <input type="search" name="s" placeholder="Search..." />
            <button type="submit" aria-label="Search">
                <img src="https://blog.anthempress.com/wp-content/uploads/2025/08/search.png" alt="Search" />
            </button>
        </form>
    </div>
    <?php
}


/**  Subjects Select drop down **/

function enqueue_custom_filter_script() {
    wp_enqueue_script(
        'subject-filters-js', // Updated handle
        get_stylesheet_directory_uri() . '/assets/js/subject-filters.js', // New path
        array(), // No dependencies
        filemtime(get_stylesheet_directory() . '/assets/js/subject-filters.js'), // Version based on file modification time
        true // Load in footer
    );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_filter_script');

function filter_post_status($query) {
    if (!is_admin() && $query->is_main_query()) {
        $query->set('post_status', 'publish');
    }
}
add_action('pre_get_posts', 'filter_post_status', 10);


/**
 * Custom template tags
 */
require get_stylesheet_directory() . '/inc/template-tags.php'; 

/** JS to open Search form */
function child_theme_enqueue_scripts() {
    wp_enqueue_script( 'nav-search-toggle', get_stylesheet_directory_uri() . '/assets/js/nav-search-toggle.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_scripts' );


// Restrict search to posts only
add_action('pre_get_posts', function ($query) {
    if ($query->is_search() && $query->is_main_query() && !is_admin()) {
        $query->set('post_type', 'post'); // Only blog posts
    }
});


// Prevent empty or whitespace-only searches
add_action('template_redirect', function () {
    if (is_search() && isset($_GET['s'])) {
        $search = trim($_GET['s']);
        if ($search === '') {
            // Redirect to blog page instead of showing empty results
            wp_redirect(home_url('/')); 
            exit;
        }
    }
});


// View MOre Java sript //

function twentytwentyfive_child_scripts() {
    // Enqueue the view-more.js script
    wp_enqueue_script(
        'twentytwentyfive-child-view-more',
        get_stylesheet_directory_uri() . '/assets/js/view-more.js',
        array(), // No dependencies
        '1.0.0', // Version number
        true // Load in footer
    );
}
add_action('wp_enqueue_scripts', 'twentytwentyfive_child_scripts');

// Code to upload images to API //

add_action('rest_api_init', 'register_rest_images');
function register_rest_images(){
    register_rest_field( array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function get_rest_featured_image($object, $field_name, $request) {
    if ($object['featured_media']) {
        $img = wp_get_attachment_image_src($object['featured_media'], 'full');
        return $img[0];
    }
    return null;
}

/**
 * Prevent WordPress from redirecting ?subject= queries
 */
add_filter('redirect_canonical', function($redirect_url) {
    if (isset($_GET['subject'])) {
        return false; // Stop WP from forcing taxonomy archive redirect
    }
    return $redirect_url;
});


function custom_taxonomy_registration() {
    $labels = array(
        'name'              => _x( 'Subjects', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Subject', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Subjects', 'textdomain' ),
        'all_items'         => __( 'All Subjects', 'textdomain' ),
        'parent_item'       => __( 'Parent Subject', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Subject:', 'textdomain' ),
        'edit_item'         => __( 'Edit Subject', 'textdomain' ),
        'update_item'       => __( 'Update Subject', 'textdomain' ),
        'add_new_item'      => __( 'Add New Subject', 'textdomain' ),
        'new_item_name'     => __( 'New Subject Name', 'textdomain' ),
        'menu_name'         => __( 'Subjects', 'textdomain' ),
    );

    $args = array(
        'labels'             => $labels,
        'hierarchical'       => true,
        'public'             => false,              // no front-end archives
        'publicly_queryable' => false,              // cannot be queried directly
        'show_ui'            => true,               // visible in admin
        'show_admin_column'  => true,
        'show_in_nav_menus'  => false,
        'show_tagcloud'      => false,
        'query_var'          => true,
        'rewrite'            => false,              // prevent pretty permalinks
    );

    register_taxonomy( 'subject', array( 'post' ), $args );
}
add_action( 'init', 'custom_taxonomy_registration' );

//Restrict Search Params //

add_action('template_redirect', function () {

    // Allow admin, login, REST API
    if (
        is_admin() ||
        strpos($_SERVER['REQUEST_URI'], '/wp-json') !== false ||
        basename($_SERVER['PHP_SELF']) === 'wp-login.php'
    ) {
        return;
    }

    $allowed_params = [
        's',
        'subject',
        'content-type',
        'paged',
        'per_page',
        '_embed',
        'page',
        'search',
        'categories',
        'tags'
    ];

    // Only keep params that are on the allow-list, and only redirect if we
    // actually found something to strip. Redirecting to home_url() with the
    // untouched REQUEST_URI (as before) sent the disallowed param right back
    // in the Location header, so the request looped forever - real inbound
    // links (UTM/fbclid/gclid params) on any single post never resolved.
    $disallowed_found = false;
    $clean_query = [];
    foreach ($_GET as $key => $value) {
        if (in_array($key, $allowed_params, true)) {
            $clean_query[$key] = $value;
        } else {
            $disallowed_found = true;
        }
    }

    if ($disallowed_found) {
        $path = strtok($_SERVER['REQUEST_URI'], '?');
        $clean_url = $clean_query ? $path . '?' . http_build_query($clean_query) : $path;
        wp_redirect(home_url($clean_url), 301);
        exit;
    }

});
