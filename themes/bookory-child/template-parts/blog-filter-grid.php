<?php
/**
 * Filtered blog grid used on the blog home.
 *
 * @package Bookory_Child
 */

$content_types       = bookory_child_content_types();
$current_content_type = bookory_child_filter_value( 'content-type' );
$current_subject_slug = bookory_child_filter_value( 'subject' );
$current_subject      = $current_subject_slug ? get_term_by( 'slug', $current_subject_slug, 'subject' ) : null;
$base_url             = bookory_child_blog_url();
$paged                = max( 1, absint( get_query_var( 'paged' ) ) );

if ( ! isset( $content_types[ $current_content_type ] ) ) {
    $current_content_type = '';
}

$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 9,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
);

$tax_query = array();

if ( $current_subject_slug ) {
    $tax_query[] = array(
        'taxonomy' => 'subject',
        'field'    => 'slug',
        'terms'    => $current_subject_slug,
    );
}

if ( $current_content_type ) {
    $tax_query[] = array(
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => $current_content_type,
    );
}

if ( count( $tax_query ) > 1 ) {
    $tax_query = array_merge( array( 'relation' => 'AND' ), $tax_query );
}

if ( $tax_query ) {
    $args['tax_query'] = $tax_query;
}

$blog_query = new WP_Query( $args );

if ( ( $current_content_type || $current_subject_slug ) && ! $blog_query->have_posts() ) {
    status_header( 404 );
    nocache_headers();

    global $wp_query;
    if ( $wp_query instanceof WP_Query ) {
        $wp_query->set_404();
    }
}
?>

<div class="blog-filters-system">
    <div class="content-type-buttons">
        <div class="buttons-container">
            <?php
            $button_count = count( $content_types );
            $button_index = 0;
            foreach ( $content_types as $slug => $name ) :
                ++$button_index;
                $filter_args = array( 'content-type' => $slug );
                if ( $current_subject_slug ) {
                    $filter_args['subject'] = $current_subject_slug;
                }
                ?>
                <a href="<?php echo esc_url( bookory_child_filter_url( $filter_args, $base_url ) ); ?>"
                   class="content-type-button <?php echo esc_attr( $current_content_type === $slug ? 'active' : '' ); ?>">
                    <?php echo esc_html( $name ); ?>
                </a>
                <?php if ( $button_index < $button_count && ! in_array( $button_index, array( 5, 10 ), true ) ) : ?>
                    <span class="button-separator">|</span>
                <?php endif; ?>
                <?php if ( in_array( $button_index, array( 5, 10 ), true ) ) : ?>
                    <br>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mobile-content-type-dropdown">
        <input type="checkbox" id="content-type-toggle" class="dropdown-toggle">
        <label for="content-type-toggle" class="dropdown-label">
            <?php echo esc_html( $current_content_type ? $content_types[ $current_content_type ] : __( 'Content Types', 'bookory-child' ) ); ?>
            <span class="dropdown-arrow" aria-hidden="true"></span>
        </label>
        <div class="dropdown-content">
            <?php
            $clear_content_type_url = $current_subject_slug
                ? bookory_child_filter_url( array( 'subject' => $current_subject_slug ), $base_url )
                : $base_url;
            ?>
            <a href="<?php echo esc_url( $clear_content_type_url ); ?>" class="<?php echo esc_attr( ! $current_content_type ? 'active' : '' ); ?>">
                <?php esc_html_e( 'Content Types', 'bookory-child' ); ?>
            </a>
            <?php foreach ( $content_types as $slug => $name ) : ?>
                <?php
                $filter_args = array( 'content-type' => $slug );
                if ( $current_subject_slug ) {
                    $filter_args['subject'] = $current_subject_slug;
                }
                ?>
                <a href="<?php echo esc_url( bookory_child_filter_url( $filter_args, $base_url ) ); ?>"
                   class="<?php echo esc_attr( $current_content_type === $slug ? 'active' : '' ); ?>">
                    <?php echo esc_html( $name ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="filter-layout">
    <aside class="subject-categories">
        <h3><?php esc_html_e( 'Subject', 'bookory-child' ); ?></h3>
        <?php
        $subjects = get_terms(
            array(
                'taxonomy'   => 'subject',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            )
        );
        ?>
        <ul class="desktop-filters">
            <?php if ( ! empty( $subjects ) && ! is_wp_error( $subjects ) ) : ?>
                <?php foreach ( $subjects as $subject ) : ?>
                    <?php
                    $filter_args = array( 'subject' => $subject->slug );
                    if ( $current_content_type ) {
                        $filter_args['content-type'] = $current_content_type;
                    }
                    ?>
                    <li>
                        <a href="<?php echo esc_url( bookory_child_filter_url( $filter_args, $base_url ) ); ?>"
                           class="<?php echo esc_attr( $current_subject_slug === $subject->slug ? 'active' : '' ); ?>">
                            <?php echo esc_html( $subject->name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <div class="mobile-dropdown">
            <input type="checkbox" id="subject-toggle" class="dropdown-toggle">
            <label for="subject-toggle" class="dropdown-label">
                <?php echo esc_html( $current_subject ? $current_subject->name : __( 'Subjects', 'bookory-child' ) ); ?>
                <span class="dropdown-arrow" aria-hidden="true"></span>
            </label>
            <div class="dropdown-content">
                <?php
                $clear_subject_url = $current_content_type
                    ? bookory_child_filter_url( array( 'content-type' => $current_content_type ), $base_url )
                    : $base_url;
                ?>
                <a href="<?php echo esc_url( $clear_subject_url ); ?>" class="<?php echo esc_attr( ! $current_subject_slug ? 'active' : '' ); ?>">
                    <?php esc_html_e( 'Subjects', 'bookory-child' ); ?>
                </a>
                <?php if ( ! empty( $subjects ) && ! is_wp_error( $subjects ) ) : ?>
                    <?php foreach ( $subjects as $subject ) : ?>
                        <?php
                        $filter_args = array( 'subject' => $subject->slug );
                        if ( $current_content_type ) {
                            $filter_args['content-type'] = $current_content_type;
                        }
                        ?>
                        <a href="<?php echo esc_url( bookory_child_filter_url( $filter_args, $base_url ) ); ?>"
                           class="<?php echo esc_attr( $current_subject_slug === $subject->slug ? 'active' : '' ); ?>">
                            <?php echo esc_html( $subject->name ); ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </aside>

    <main class="posts-grid">
        <?php if ( $current_content_type || $current_subject_slug ) : ?>
            <div class="active-filters">
                <?php if ( $current_content_type ) : ?>
                    <span class="active-filter">
                        <?php esc_html_e( 'Content Type:', 'bookory-child' ); ?>
                        <?php echo esc_html( $content_types[ $current_content_type ] ); ?>
                        <a href="<?php echo esc_url( $current_subject_slug ? bookory_child_filter_url( array( 'subject' => $current_subject_slug ), $base_url ) : $base_url ); ?>"
                           class="remove-filter" aria-label="<?php esc_attr_e( 'Remove content type filter', 'bookory-child' ); ?>">×</a>
                    </span>
                <?php endif; ?>
                <?php if ( $current_subject_slug && $current_subject ) : ?>
                    <span class="active-filter">
                        <?php esc_html_e( 'Subject:', 'bookory-child' ); ?>
                        <?php echo esc_html( $current_subject->name ); ?>
                        <a href="<?php echo esc_url( $current_content_type ? bookory_child_filter_url( array( 'content-type' => $current_content_type ), $base_url ) : $base_url ); ?>"
                           class="remove-filter" aria-label="<?php esc_attr_e( 'Remove subject filter', 'bookory-child' ); ?>">×</a>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $blog_query->have_posts() ) : ?>
            <div class="posts-row">
                <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                    <?php $image_url = bookory_child_default_featured_image_url(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                        <div class="post-content">
                            <?php $label = bookory_child_post_content_type_label(); ?>
                            <?php if ( $label ) : ?>
                                <div class="content-type-header">
                                    <span class="content-type-label"><?php echo esc_html( $label ); ?></span>
                                </div>
                            <?php endif; ?>

                            <h2>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="post-media">
                                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'post-image' ) ); ?>
                                    <?php elseif ( $image_url ) : ?>
                                        <img src="<?php echo esc_url( $image_url ); ?>" class="post-image" alt="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php else : ?>
                                        <div class="default-post-image">
                                            <span><?php the_title(); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>

                            <div class="post-excerpt">
                                <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ( $blog_query->max_num_pages > 1 ) : ?>
                <?php
                $pagination_args = array_filter(
                    array(
                        'content-type' => $current_content_type,
                        'subject'      => $current_subject_slug,
                    )
                );
                $pagination_base = add_query_arg( $pagination_args, remove_query_arg( array( 'paged', 'page' ), $base_url ) );
                $pagination_base = add_query_arg( 'paged', 999999999, $pagination_base );
                ?>
                <div class="pagination">
                    <?php
                    echo paginate_links(
                        array(
                            'base'      => str_replace( '999999999', '%#%', esc_url( $pagination_base ) ),
                            'format'    => '',
                            'current'   => $paged,
                            'total'     => (int) $blog_query->max_num_pages,
                            'mid_size'  => 2,
                            'prev_text' => __( '« Previous', 'bookory-child' ),
                            'next_text' => __( 'Next »', 'bookory-child' ),
                            'add_args'  => false,
                        )
                    );
                    ?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <p class="no-posts"><?php esc_html_e( 'No posts found matching your criteria.', 'bookory-child' ); ?></p>
        <?php endif; ?>
    </main>
</div>

<?php wp_reset_postdata(); ?>
