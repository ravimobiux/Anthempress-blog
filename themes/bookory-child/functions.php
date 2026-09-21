<?php
/**
 * Bookory Child Theme functions and definitions.
 *
 * The blog-specific behavior in this file mirrors the Twenty Twenty-Five
 * child theme while keeping Bookory as the parent theme.
 *
 * @package Bookory_Child
 */

/**
 * Content types exposed by the blog filters.
 *
 * These are category slugs, retained for compatibility with the existing
 * Anthem Press content model.
 */
function bookory_child_content_types() {
    return array(
        'interviews'                    => 'Author Interview',
        'guest-blog'                    => 'Guest Post',
        'series-spotlight'              => 'Series Spotlight',
        'new-release'                   => 'New Release',
        'anthem-company-news'           => 'Company News',
        'media-coverage'                => 'Media Coverage',
        'call-for-proposal'             => 'Call for Proposal',
        'meet-the-author'               => 'Meet the Author',
        'podcast'                       => 'Podcast & Video',
        'open-access'                   => 'Open Access',
        'publishing-news'               => 'Publishing Industry News',
        'meet-the-series-advisor'       => 'Meet the Series Advisor',
        'university-press-blog-roundup' => 'University Press Blog Roundup',
    );
}

/**
 * Add the small amount of theme setup used by the reference child theme.
 */
function bookory_child_setup() {
    register_nav_menus(
        array(
            'mobile' => __( 'Mobile Menu', 'bookory-child' ),
        )
    );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'sidebar-featured', 400, 300, true );
    load_theme_textdomain( 'bookory-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'bookory_child_setup', 11 );

/**
 * The child header/footer replace Bookory's default chrome on blog pages.
 */
function bookory_child_disable_parent_chrome() {
    remove_action( 'wp_footer', 'bookory_mobile_nav', 1 );
    remove_action( 'wp_footer', 'bookory_template_account_dropdown', 1 );
}
add_action( 'after_setup_theme', 'bookory_child_disable_parent_chrome', 20 );

/**
 * Get a validated filter value from the current request.
 *
 * @param string $key Request key.
 * @return string
 */
function bookory_child_filter_value( $key ) {
    if ( ! isset( $_GET[ $key ] ) || ! is_scalar( $_GET[ $key ] ) ) {
        return '';
    }

    $value = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );

    return preg_match( '/^[a-zA-Z0-9_-]+$/', $value ) ? $value : '';
}

/**
 * URL used by the blog filter links.
 *
 * @return string
 */
function bookory_child_blog_url() {
    $url = get_post_type_archive_link( 'post' );

    return trailingslashit( $url ? $url : home_url( '/' ) );
}

/**
 * Build a blog URL while preserving the other active filter.
 *
 * @param array  $args Query arguments to add.
 * @param string $base Base URL.
 * @return string
 */
function bookory_child_filter_url( $args = array(), $base = '' ) {
    $base = $base ? $base : bookory_child_blog_url();
    $base = remove_query_arg( array( 'paged', 'page' ), $base );

    return add_query_arg( array_filter( $args ), $base );
}

/**
 * Enqueue parent/child styles and all reference-theme interactions.
 */
function bookory_child_enqueue_assets() {
    // Bookory already loads its parent stylesheet. Re-register the child
    // stylesheet after Bookory's child_scripts() hook so the reference blog
    // styles are the final cascade on the page.
    if ( wp_style_is( 'bookory-child-style', 'enqueued' ) ) {
        wp_dequeue_style( 'bookory-child-style' );
    }

    if ( wp_style_is( 'bookory-child-style', 'registered' ) ) {
        wp_deregister_style( 'bookory-child-style' );
    }

    wp_enqueue_style(
        'bookory-child-style',
        get_stylesheet_uri(),
        array( 'bookory-style' ),
        file_exists( get_stylesheet_directory() . '/style.css' )
            ? filemtime( get_stylesheet_directory() . '/style.css' )
            : wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'bookory-child-font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    $script_uri = get_stylesheet_directory_uri() . '/assets/js/';
    $script_dir = get_stylesheet_directory() . '/assets/js/';
    $root_script = get_stylesheet_directory() . '/script.js';

    $scripts = array(
        'bookory-child-script'          => 'script.js',
        'bookory-child-blog-filters'    => 'blog-filters.js',
        'bookory-child-search-toggle'   => 'nav-search-toggle.js',
        'bookory-child-subject-filters' => 'subject-filters.js',
        'bookory-child-view-more'       => 'view-more.js',
    );

    foreach ( $scripts as $handle => $filename ) {
        $path = 'script.js' === $filename ? $root_script : $script_dir . $filename;

        if ( file_exists( $path ) ) {
            wp_enqueue_script(
                $handle,
                'script.js' === $filename ? get_stylesheet_directory_uri() . '/script.js' : $script_uri . $filename,
                array(),
                filemtime( $path ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'bookory_child_enqueue_assets', 40 );

/**
 * Register the subject taxonomy used by the filter UI when no plugin already
 * provides it.
 */
function bookory_child_register_subject_taxonomy() {
    if ( taxonomy_exists( 'subject' ) ) {
        return;
    }

    register_taxonomy(
        'subject',
        array( 'post' ),
        array(
            'labels'             => array(
                'name'          => _x( 'Subjects', 'taxonomy general name', 'bookory-child' ),
                'singular_name' => _x( 'Subject', 'taxonomy singular name', 'bookory-child' ),
                'search_items'  => __( 'Search Subjects', 'bookory-child' ),
                'all_items'     => __( 'All Subjects', 'bookory-child' ),
                'edit_item'     => __( 'Edit Subject', 'bookory-child' ),
                'update_item'   => __( 'Update Subject', 'bookory-child' ),
                'add_new_item'  => __( 'Add New Subject', 'bookory-child' ),
                'menu_name'     => __( 'Subjects', 'bookory-child' ),
            ),
            'hierarchical'       => true,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_admin_column'  => true,
            'show_in_nav_menus'  => false,
            'show_tagcloud'      => false,
            // Subject is used as a filter parameter by the blog template,
            // not as a standalone front-end archive.
            'query_var'          => false,
            'rewrite'            => false,
            'show_in_rest'       => true,
        )
    );
}
add_action( 'init', 'bookory_child_register_subject_taxonomy', 20 );

/**
 * Keep ?subject=slug on the blog/search template instead of allowing
 * WordPress to convert it into a /subject/slug/ taxonomy archive.
 *
 * The request parameter remains available in $_GET for the custom WP_Query.
 * This also protects the site when another plugin registers the taxonomy
 * with query_var enabled.
 *
 * @param array $query_vars Parsed public query variables.
 * @return array
 */
function bookory_child_keep_subject_filter_on_blog( $query_vars ) {
    if ( isset( $_GET['subject'] ) && is_scalar( $_GET['subject'] ) ) {
        unset( $query_vars['subject'] );

        if ( isset( $query_vars['taxonomy'] ) && 'subject' === $query_vars['taxonomy'] ) {
            unset( $query_vars['taxonomy'], $query_vars['term'] );
        }
    }

    return $query_vars;
}
add_filter( 'request', 'bookory_child_keep_subject_filter_on_blog', 1 );

/**
 * Keep blog/search queries limited to published posts.
 */
function bookory_child_filter_main_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        $query->set( 'post_status', 'publish' );

        if ( $query->is_search() ) {
            $query->set( 'post_type', 'post' );
        }
    }
}
add_action( 'pre_get_posts', 'bookory_child_filter_main_query', 10 );

/**
 * Do not let an empty search render an empty search-results page.
 */
function bookory_child_redirect_empty_search() {
    if ( is_admin() || ! is_search() || ! isset( $_GET['s'] ) ) {
        return;
    }

    $search = is_scalar( $_GET['s'] ) ? trim( wp_unslash( $_GET['s'] ) ) : '';

    if ( '' === $search ) {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}
add_action( 'template_redirect', 'bookory_child_redirect_empty_search', 1 );

/**
 * Preserve query-string filter URLs instead of redirecting them to a
 * taxonomy archive.
 */
function bookory_child_disable_filter_canonical_redirect( $redirect_url ) {
    if ( isset( $_GET['subject'] ) || isset( $_GET['content-type'] ) ) {
        return false;
    }

    return $redirect_url;
}
add_filter( 'redirect_canonical', 'bookory_child_disable_filter_canonical_redirect' );

/**
 * Strip unrecognised front-end query parameters, matching the reference
 * theme’s clean blog URLs. WooCommerce and non-blog requests are excluded.
 */
function bookory_child_clean_front_end_query() {
    if (
        is_admin() ||
        wp_doing_ajax() ||
        is_feed() ||
        ( function_exists( 'is_rest' ) && is_rest() ) ||
        ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ||
        ! ( is_home() || is_search() || is_archive() || is_singular( 'post' ) )
    ) {
        return;
    }

    $allowed_params = array(
        's',
        'subject',
        'content-type',
        'paged',
        'per_page',
        '_embed',
        'page',
        'search',
        'categories',
        'tags',
    );

    $clean_query      = array();
    $disallowed_found = false;

    foreach ( $_GET as $key => $value ) {
        if ( in_array( $key, $allowed_params, true ) ) {
            $clean_query[ $key ] = $value;
        } else {
            $disallowed_found = true;
        }
    }

    if ( ! $disallowed_found || empty( $_SERVER['REQUEST_URI'] ) ) {
        return;
    }

    $path = wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );
    $path = $path ? $path : '/';
    $url  = $path;

    if ( ! empty( $clean_query ) ) {
        $url .= '?' . http_build_query( $clean_query );
    }

    wp_safe_redirect( home_url( $url ), 301 );
    exit;
}
add_action( 'template_redirect', 'bookory_child_clean_front_end_query', 20 );

/**
 * Add a featured-image URL to post REST responses.
 */
function bookory_child_register_rest_images() {
    register_rest_field(
        array( 'post' ),
        'fimg_url',
        array(
            'get_callback'    => 'bookory_child_get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action( 'rest_api_init', 'bookory_child_register_rest_images' );

function bookory_child_get_rest_featured_image( $object ) {
    if ( empty( $object['featured_media'] ) ) {
        return bookory_child_default_featured_image_url();
    }

    $image = wp_get_attachment_image_src( (int) $object['featured_media'], 'full' );

    return $image ? $image[0] : bookory_child_default_featured_image_url();
}

/**
 * Output the reference-theme header while retaining WordPress menu support.
 */
function bookory_child_custom_header() {
    $logo = get_custom_logo();

    if ( ! $logo ) {
        $logo = sprintf(
            '<a href="%1$s" rel="home"><img src="%2$s" alt="%3$s"></a>',
            esc_url( home_url( '/' ) ),
            esc_url( 'https://blog.anthempress.com/wp-content/uploads/2025/08/AnthemFulllogo.png' ),
            esc_attr( get_bloginfo( 'name' ) )
        );
    }
    ?>
    <header class="custom-header">
        <div class="header-top">
            <div class="logo-container">
                <?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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

        <div class="mobile-top-bar">
            <div class="hamburger-container">
                <input type="checkbox" id="menu-toggle" class="menu-toggle" aria-label="Toggle menu">
                <label for="menu-toggle" class="hamburger-btn open-btn" aria-label="Open menu">☰</label>
                <label for="menu-toggle" class="hamburger-btn close-btn" aria-label="Close menu">✕</label>
            </div>
            <div class="nav-search-inline">
                <a href="#nav-search-bar" class="nav-search-link" data-nav-search-toggle role="button">
                    <span><?php esc_html_e( 'Search', 'bookory-child' ); ?></span>
                    <i class="fas fa-search" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="nav-wrapper">
            <div class="nav-container">
                <nav class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'bookory-child' ); ?>">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav>

                <div class="nav-search-inline-desktop">
                    <a href="#nav-search-bar" class="nav-search-link" data-nav-search-toggle role="button">
                        <span><?php esc_html_e( 'Search', 'bookory-child' ); ?></span>
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div id="nav-search-bar" class="nav-search-bar" hidden>
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="nav-search-input"><?php esc_html_e( 'Search blog posts', 'bookory-child' ); ?></label>
            <input id="nav-search-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search...', 'bookory-child' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
            <button type="submit" aria-label="<?php esc_attr_e( 'Search', 'bookory-child' ); ?>">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </form>
    </div>
    <?php
}

/**
 * Load child-theme helpers after the parent has loaded its functions.
 */
$bookory_child_template_tags = get_stylesheet_directory() . '/inc/template-tags.php';
if ( file_exists( $bookory_child_template_tags ) ) {
    require_once $bookory_child_template_tags;
}

/**
 * Add Anthem Press context to Bookory's native single-post header.
 */
function bookory_child_single_post_context() {
    $content_type = '';
    $content_types = bookory_child_content_types();
    $categories = get_the_category();
    foreach ( $categories as $category ) {
        if ( isset( $content_types[ $category->slug ] ) ) {
            $content_type = $content_types[ $category->slug ];
            break;
        }
    }
    $subjects = get_the_terms( get_the_ID(), 'subject' );

    if ( ! $content_type && ( empty( $subjects ) || is_wp_error( $subjects ) ) ) {
        return;
    }
    ?>
    <div class="bookory-child-single-context">
        <?php if ( $content_type ) : ?>
            <span class="bookory-child-context-type"><?php echo esc_html( $content_type ); ?></span>
        <?php endif; ?>

        <?php if ( ! empty( $subjects ) && ! is_wp_error( $subjects ) ) : ?>
            <div class="bookory-child-context-subjects" aria-label="<?php esc_attr_e( 'Subjects', 'bookory-child' ); ?>">
                <span class="bookory-child-context-label"><?php esc_html_e( 'Subjects', 'bookory-child' ); ?></span>
                <?php foreach ( $subjects as $subject ) : ?>
                    <a href="<?php echo esc_url( bookory_child_filter_url( array( 'subject' => $subject->slug ) ) ); ?>">
                        <?php echo esc_html( $subject->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
add_action( 'bookory_single_post', 'bookory_child_single_post_context', 10 );

/**
 * Keep Bookory's native single-post hero, while allowing ACF's cover_image
 * field to supply the image when the editor has used it instead.
 */
function bookory_child_use_cover_image_in_native_single() {
    if ( function_exists( 'bookory_post_thumbnail' ) && function_exists( 'bookory_child_post_sidebar' ) ) {
        remove_action( 'bookory_single_post_top', 'bookory_post_thumbnail', 10 );
        add_action( 'bookory_single_post_top', 'bookory_child_post_sidebar', 10 );
    }
}
add_action( 'after_setup_theme', 'bookory_child_use_cover_image_in_native_single', 30 );

// Place the WordPress featured image between Bookory's native header and body.
add_action( 'bookory_single_post', 'bookory_child_render_featured_image', 25 );

/**
 * Replace Bookory's category/author metadata with the requested date and
 * reading-time metadata on single posts.
 */
function bookory_child_single_post_header() {
    $content   = strip_shortcodes( get_the_content() );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $reading_time = max( 1, (int) ceil( $word_count / 200 ) );
    ?>
    <header class="entry-header">
        <div class="entry-meta">
            <div class="posted-on">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
                </time>
            </div>
            <div class="reading-time">
                <?php echo esc_html( $reading_time ); ?> <?php esc_html_e( 'min read', 'bookory-child' ); ?>
            </div>
        </div>
        <?php the_title( '<h1 class="alpha entry-title">', '</h1>' ); ?>
    </header><!-- .entry-header -->
    <?php
}

/**
 * Use the simplified single-post header while retaining Bookory's layout.
 */
function bookory_child_use_simplified_single_header() {
    remove_action( 'bookory_single_post', 'bookory_child_single_post_context', 10 );
    remove_action( 'bookory_single_post', 'bookory_post_header', 20 );
    add_action( 'bookory_single_post', 'bookory_child_single_post_header', 20 );
}
add_action( 'after_setup_theme', 'bookory_child_use_simplified_single_header', 31 );

/**
 * Render the child-theme single-post navigation with text-only links.
 */
function bookory_child_post_nav() {
    $previous = get_previous_post();
    $next     = get_next_post();

    if ( ! $previous && ! $next ) {
        return;
    }

    ?>
    <nav id="post-navigation" class="navigation post-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Post Navigation', 'bookory-child' ); ?>">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'bookory-child' ); ?></h2>
        <div class="nav-links">
            <?php if ( $next ) : ?>
                <div class="nav-next">
                    <a class="bookory-child-nav-link" href="<?php echo esc_url( get_permalink( $next ) ); ?>" rel="next" aria-label="<?php echo esc_attr( sprintf( __( 'Next post: %s', 'bookory-child' ), get_the_title( $next ) ) ); ?>">
                        <span class="nav-content">
                            <span class="reader-text"><?php esc_html_e( 'Next', 'bookory-child' ); ?></span>
                            <span class="title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
                        </span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $previous ) : ?>
                <div class="nav-previous">
                    <a class="bookory-child-nav-link" href="<?php echo esc_url( get_permalink( $previous ) ); ?>" rel="prev" aria-label="<?php echo esc_attr( sprintf( __( 'Previous post: %s', 'bookory-child' ), get_the_title( $previous ) ) ); ?>">
                        <span class="nav-content">
                            <span class="reader-text"><?php esc_html_e( 'Previous', 'bookory-child' ); ?></span>
                            <span class="title"><?php echo esc_html( get_the_title( $previous ) ); ?></span>
                        </span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

/**
 * Replace broken blog images with the appropriate site fallback.
 */
function bookory_child_enqueue_image_fallback_script() {
    if ( ! is_home() && ! is_archive() && ! is_search() && ! is_singular( 'post' ) ) {
        return;
    }

    $featured_url = wp_json_encode( bookory_child_default_featured_image_url() );
    $cover_url    = wp_json_encode( bookory_child_default_cover_image_url() );
    ?>
    <script id="bookory-child-image-fallback">
        document.addEventListener('error', function (event) {
            const image = event.target;

            if (!(image instanceof HTMLImageElement)) {
                return;
            }

            if (image.matches('.entry-content img')) {
                const figure = image.closest('figure, .wp-block-image');
                const paragraph = image.closest('p');

                if (figure) {
                    figure.remove();
                } else if (paragraph && !paragraph.textContent.trim()) {
                    paragraph.remove();
                } else {
                    image.remove();
                }

                return;
            }

            const fallback = image.matches('.post-thumbnail.is_single img')
                ? <?php echo $cover_url; ?>
                : image.matches('.post-media img, .archive-post-thumbnail img, .search-result-thumbnail img, .latest-post-image img, .bookory-child-featured-image img')
                    ? <?php echo $featured_url; ?>
                    : '';

            if (!fallback || image.dataset.bookoryFallbackApplied === '1') {
                return;
            }

            image.dataset.bookoryFallbackApplied = '1';
            image.removeAttribute('srcset');
            image.removeAttribute('sizes');
            image.src = fallback;
        }, true);
    </script>
    <?php
}
add_action( 'wp_head', 'bookory_child_enqueue_image_fallback_script', 99 );
