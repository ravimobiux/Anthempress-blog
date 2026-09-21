<?php
/**
 * Template helpers for the Bookory child theme.
 *
 * @package Bookory_Child
 */

function bookory_child_default_featured_image_url() {
    return content_url( 'uploads/2026/09/Anthempress_Featured_image.png' );
}

function bookory_child_default_cover_image_url() {
    return content_url( 'uploads/2026/09/Anthempress_cover_Image.png' );
}

/**
 * Backwards-compatible alias for the single-post cover fallback.
 *
 * @return string
 */
function bookory_child_default_image_url() {
    return bookory_child_default_cover_image_url();
}

function bookory_child_post_content_type_label() {
    $content_types = bookory_child_content_types();
    $categories    = get_the_category();

    foreach ( $categories as $category ) {
        if ( isset( $content_types[ $category->slug ] ) ) {
            return $content_types[ $category->slug ];
        }
    }

    return ! empty( $categories ) ? $categories[0]->name : '';
}

function bookory_child_first_content_image_url() {
    $content = get_the_content();

    if ( preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $content, $matches ) ) {
        return esc_url( $matches[1] );
    }

    return '';
}

/**
 * Render the common latest-posts block used by archive and single views.
 *
 * @param int $exclude_id Post ID to omit.
 */
function bookory_child_render_latest_posts( $exclude_id = 0 ) {
    $latest_query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 4,
            'post__not_in'        => $exclude_id ? array( (int) $exclude_id ) : array(),
            'ignore_sticky_posts' => true,
        )
    );

    if ( ! $latest_query->have_posts() ) {
        return;
    }
    ?>
    <section class="latest-posts-grid">
        <h2 class="latest-posts-title"><?php esc_html_e( 'Latest Posts', 'bookory-child' ); ?></h2>
        <div class="latest-posts-container">
            <?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
                <article class="latest-post-card">
                    <div class="latest-post-inner">
                        <a href="<?php the_permalink(); ?>" class="latest-post-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium' ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( bookory_child_default_featured_image_url() ); ?>"
                                     alt="<?php echo esc_attr( get_the_title() ); ?>">
                            <?php endif; ?>
                        </a>
                        <div class="latest-post-content">
                            <h3 class="latest-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="latest-post-excerpt">
                                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?>
                            </p>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
}

/**
 * Render the reference-theme sidebar widgets.
 *
 * @param int $exclude_id Post ID to omit from recent posts.
 */
function bookory_child_render_blog_sidebar( $exclude_id = 0 ) {
    $recent_posts  = get_posts(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'numberposts'         => 5,
            'post__not_in'        => $exclude_id ? array( (int) $exclude_id ) : array(),
            'ignore_sticky_posts' => true,
        )
    );
    $content_types = bookory_child_content_types();
    $base_url      = bookory_child_blog_url();
    ?>
    <div class="sidebar-widget recent-posts">
        <h3 class="widget-title"><?php esc_html_e( 'Recent Posts', 'bookory-child' ); ?></h3>
        <ul>
            <?php foreach ( $recent_posts as $recent_post ) : ?>
                <li>
                    <a href="<?php echo esc_url( get_permalink( $recent_post->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $recent_post->ID ) ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="sidebar-widget categories">
        <h3 class="widget-title"><?php esc_html_e( 'Categories', 'bookory-child' ); ?></h3>
        <ul>
            <?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $category ) : ?>
                <?php
                $category_url = isset( $content_types[ $category->slug ] )
                    ? bookory_child_filter_url( array( 'content-type' => $category->slug ), $base_url )
                    : get_category_link( $category->term_id );
                ?>
                <li>
                    <a href="<?php echo esc_url( $category_url ); ?>">
                        <?php echo esc_html( $category->name ); ?>
                        <span class="archive-count">(<?php echo esc_html( $category->count ); ?>)</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="sidebar-widget archives">
        <h3 class="widget-title"><?php esc_html_e( 'Archives', 'bookory-child' ); ?></h3>
        <ul class="archives-list">
            <?php echo bookory_child_archive_links( 8 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </ul>
        <ul class="archives-list-full">
            <?php echo bookory_child_archive_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </ul>
        <button class="view-more-archives" type="button"><?php esc_html_e( 'View More', 'bookory-child' ); ?></button>
    </div>
    <?php
}

function bookory_child_archive_links( $limit = 0 ) {
    $args = array(
        'type'            => 'monthly',
        'show_post_count' => true,
        'echo'            => 0,
    );

    if ( $limit > 0 ) {
        $args['limit'] = absint( $limit );
    }

    $html = wp_get_archives( $args );

    if ( ! $html ) {
        return '';
    }

    return preg_replace(
        '/<\/a>&nbsp;\((\d+)\)/',
        ' <span class="archive-count">($1)</span></a>',
        $html
    );
}

/**
 * Render the cover image used in the left single-post sidebar.
 */
function bookory_child_post_sidebar() {
    $cover_image = function_exists( 'get_field' ) ? get_field( 'cover_image' ) : null;
    $image_id    = 0;
    $image_url   = '';
    $image_alt   = get_the_title();

    if ( is_array( $cover_image ) ) {
        $image_id  = ! empty( $cover_image['ID'] ) ? (int) $cover_image['ID'] : ( ! empty( $cover_image['id'] ) ? (int) $cover_image['id'] : 0 );
        $image_url = ! empty( $cover_image['url'] ) ? $cover_image['url'] : '';
        $image_alt = ! empty( $cover_image['alt'] ) ? $cover_image['alt'] : $image_alt;
    } elseif ( is_numeric( $cover_image ) ) {
        $image_id = (int) $cover_image;
    }

    if ( $image_id ) {
        $image_url = wp_get_attachment_image_url( $image_id, 'large' );
        $alt_meta  = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
        $image_alt = $alt_meta ? $alt_meta : $image_alt;
    }

    if ( ! $image_url ) {
        $image_url = bookory_child_default_cover_image_url();
    }
    ?>
    <div class="post-thumbnail is_single">
        <?php if ( $image_id ) : ?>
            <?php
            echo wp_get_attachment_image(
                $image_id,
                'large',
                false,
                array(
                    'class'   => 'featured-image',
                    'alt'     => $image_alt,
                    'loading' => 'lazy',
                    'sizes'   => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 800px',
                )
            );
            ?>
        <?php else : ?>
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="featured-image" loading="lazy" width="1810" height="869">
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Return the attachment ID stored in the optional ACF cover_image field.
 *
 * @return int
 */
function bookory_child_cover_image_id() {
    $cover_image = function_exists( 'get_field' ) ? get_field( 'cover_image' ) : null;

    if ( is_array( $cover_image ) ) {
        return ! empty( $cover_image['ID'] )
            ? (int) $cover_image['ID']
            : ( ! empty( $cover_image['id'] ) ? (int) $cover_image['id'] : 0 );
    }

    return is_numeric( $cover_image ) ? (int) $cover_image : 0;
}

/**
 * Render the WordPress featured image as the article's secondary visual.
 * The cover image remains the full-width Bookory hero above the card.
 */
function bookory_child_render_featured_image() {
    if ( ! has_post_thumbnail() ) {
        return;
    }

    $featured_id = get_post_thumbnail_id();
    $cover_id    = bookory_child_cover_image_id();
    $hero_id     = $cover_id ? $cover_id : $featured_id;

    // Do not repeat the hero when both fields use the same attachment.
    if ( $hero_id === $featured_id ) {
        return;
    }

    $image_alt = get_post_meta( $featured_id, '_wp_attachment_image_alt', true );
    $image_alt = $image_alt ? $image_alt : get_the_title();
    ?>
    <figure class="bookory-child-featured-image">
        <?php
        echo wp_get_attachment_image(
            $featured_id,
            'large',
            false,
            array(
                'class'   => 'featured-image-panel',
                'alt'     => $image_alt,
                'loading' => 'lazy',
                'sizes'   => '(max-width: 767px) 100vw, (max-width: 1100px) 90vw, 700px',
            )
        );
        ?>
    </figure>
    <?php
}
