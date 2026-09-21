<?php
/**
 * Search results template with content-type and subject filters.
 *
 * @package Bookory_Child
 */

get_header();

$content_types        = bookory_child_content_types();
$current_content_type = bookory_child_filter_value( 'content-type' );
$current_subject_slug = bookory_child_filter_value( 'subject' );
$current_subject      = $current_subject_slug ? get_term_by( 'slug', $current_subject_slug, 'subject' ) : null;
$search_term          = get_search_query( false );
$base_url             = remove_query_arg( array( 'paged', 'page', 'content-type', 'subject' ), get_search_link( $search_term ) );
$paged                = max( 1, absint( get_query_var( 'paged' ) ) );

if ( ! isset( $content_types[ $current_content_type ] ) ) {
    $current_content_type = '';
}

$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    's'                   => $search_term,
    'paged'               => $paged,
    'posts_per_page'      => 5,
    'orderby'             => 'date',
    'order'               => 'DESC',
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

$query = new WP_Query( $args );
$all_content_url = $current_subject_slug
    ? add_query_arg( array( 'subject' => $current_subject_slug ), $base_url )
    : $base_url;
$all_subject_url = $current_content_type
    ? add_query_arg( array( 'content-type' => $current_content_type ), $base_url )
    : $base_url;
?>

<main id="site-content" role="main" class="search-page">
    <div class="search-page-layout">
        <aside class="filters-column">
            <h3><?php esc_html_e( 'Content Type', 'bookory-child' ); ?></h3>
            <ul class="desktop-filters">
                <li>
                    <a href="<?php echo esc_url( $all_content_url ); ?>"
                       class="<?php echo esc_attr( ! $current_content_type ? 'active' : '' ); ?>">
                        <?php esc_html_e( 'All', 'bookory-child' ); ?>
                    </a>
                </li>
                <?php foreach ( $content_types as $slug => $name ) : ?>
                    <?php
                    $filter_args = array( 'content-type' => $slug );
                    if ( $current_subject_slug ) {
                        $filter_args['subject'] = $current_subject_slug;
                    }
                    ?>
                    <li>
                        <a href="<?php echo esc_url( add_query_arg( $filter_args, $base_url ) ); ?>"
                           class="<?php echo esc_attr( $current_content_type === $slug ? 'active' : '' ); ?>">
                            <?php echo esc_html( $name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3><?php esc_html_e( 'Subjects', 'bookory-child' ); ?></h3>
            <ul class="desktop-filters">
                <li>
                    <a href="<?php echo esc_url( $all_subject_url ); ?>"
                       class="<?php echo esc_attr( ! $current_subject_slug ? 'active' : '' ); ?>">
                        <?php esc_html_e( 'All', 'bookory-child' ); ?>
                    </a>
                </li>
                <?php
                $subjects = get_terms(
                    array(
                        'taxonomy'   => 'subject',
                        'hide_empty' => true,
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                    )
                );
                ?>
                <?php if ( ! empty( $subjects ) && ! is_wp_error( $subjects ) ) : ?>
                    <?php foreach ( $subjects as $subject ) : ?>
                        <?php
                        $filter_args = array( 'subject' => $subject->slug );
                        if ( $current_content_type ) {
                            $filter_args['content-type'] = $current_content_type;
                        }
                        ?>
                        <li>
                            <a href="<?php echo esc_url( add_query_arg( $filter_args, $base_url ) ); ?>"
                               class="<?php echo esc_attr( $current_subject_slug === $subject->slug ? 'active' : '' ); ?>">
                                <?php echo esc_html( $subject->name ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </aside>

        <div class="results-column">
            <header class="search-header">
                <h1 class="search-title">
                    <?php
                    printf(
                        esc_html__( 'Search Results for: %s', 'bookory-child' ),
                        '<span>' . esc_html( $search_term ) . '</span>'
                    );
                    if ( $current_content_type || $current_subject_slug ) {
                        echo '<span class="filter-indicator"> (' . esc_html__( 'Filtered', 'bookory-child' ) . ')</span>';
                    }
                    ?>
                </h1>

                <?php if ( $current_content_type || $current_subject_slug ) : ?>
                    <div class="active-filters">
                        <?php if ( $current_content_type ) : ?>
                            <span class="active-filter">
                                <?php esc_html_e( 'Content Type:', 'bookory-child' ); ?>
                                <?php echo esc_html( $content_types[ $current_content_type ] ); ?>
                                <a href="<?php echo esc_url( $current_subject_slug ? add_query_arg( array( 'subject' => $current_subject_slug ), $base_url ) : $base_url ); ?>"
                                   class="remove-filter" aria-label="<?php esc_attr_e( 'Remove content type filter', 'bookory-child' ); ?>">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if ( $current_subject_slug && $current_subject ) : ?>
                            <span class="active-filter">
                                <?php esc_html_e( 'Subject:', 'bookory-child' ); ?>
                                <?php echo esc_html( $current_subject->name ); ?>
                                <a href="<?php echo esc_url( $current_content_type ? add_query_arg( array( 'content-type' => $current_content_type ), $base_url ) : $base_url ); ?>"
                                   class="remove-filter" aria-label="<?php esc_attr_e( 'Remove subject filter', 'bookory-child' ); ?>">×</a>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if ( $query->have_posts() ) : ?>
                <div class="search-results-list">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <?php $image_url = bookory_child_default_featured_image_url(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
                            <div class="search-result-inner">
                                <a href="<?php the_permalink(); ?>" class="search-result-thumbnail">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'medium' ); ?>
                                    <?php elseif ( $image_url ) : ?>
                                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php else : ?>
                                        <div class="search-default-thumbnail"><h3><?php the_title(); ?></h3></div>
                                    <?php endif; ?>
                                </a>
                                <div class="search-result-content">
                                    <h2 class="search-result-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <div class="search-result-meta">
                                        <span class="search-result-date"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></span>
                                    </div>
                                    <div class="search-result-excerpt">
                                        <?php
                                        $excerpt = get_the_excerpt();
                                        if ( ! $excerpt ) {
                                            $excerpt = wp_trim_words( get_the_content(), 30 );
                                        }
                                        echo esc_html( wp_trim_words( wp_strip_all_tags( $excerpt ), 35, '...' ) );
                                        ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="search-read-more"><?php esc_html_e( 'Read More', 'bookory-child' ); ?></a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="pagination">
                    <?php
                    echo paginate_links(
                        array(
                            'base'      => add_query_arg( 'paged', '%#%', $base_url ),
                            'format'    => '',
                            'total'     => (int) $query->max_num_pages,
                            'current'   => $paged,
                            'mid_size'  => 2,
                            'prev_text' => __( '« Previous', 'bookory-child' ),
                            'next_text' => __( 'Next »', 'bookory-child' ),
                            'add_args'  => array_filter(
                                array(
                                    'content-type' => $current_content_type,
                                    'subject'      => $current_subject_slug,
                                )
                            ),
                        )
                    );
                    ?>
                </div>
            <?php else : ?>
                <div class="no-results">
                    <h2><?php esc_html_e( 'Nothing Found', 'bookory-child' ); ?></h2>
                    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'bookory-child' ); ?></p>
                    <p><?php esc_html_e( 'Or', 'bookory-child' ); ?> <a href="<?php echo esc_url( $base_url ); ?>"><?php esc_html_e( 'clear all filters', 'bookory-child' ); ?></a> <?php esc_html_e( 'to see all results.', 'bookory-child' ); ?></p>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
