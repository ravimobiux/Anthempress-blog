<?php
/**
 * Archive template matching the Twenty Twenty-Five child theme.
 *
 * @package Bookory_Child
 */

get_header();

$archive_year       = absint( get_query_var( 'year' ) );
$archive_month      = absint( get_query_var( 'monthnum' ) );
$archive_month_name = $archive_month ? wp_date( 'F', mktime( 0, 0, 0, $archive_month, 1 ) ) : '';
?>

<main id="primary" class="site-main">
    <div class="container alignwide">
        <header class="page-header">
            <?php if ( $archive_month && $archive_year ) : ?>
                <div class="archive-period">
                    <span class="archive-period-label"><?php esc_html_e( 'Month:', 'bookory-child' ); ?></span>
                    <span class="archive-period-value"><?php echo esc_html( $archive_month_name . ' ' . $archive_year ); ?></span>
                </div>
            <?php endif; ?>
            <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
            <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
        </header>

        <div class="archive-content-wrapper">
            <div class="archive-main-content">
                <?php if ( have_posts() ) : ?>
                    <div class="archive-posts-grid">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-post-card' ); ?>>
                                <div class="archive-post-inner">
                                    <a href="<?php the_permalink(); ?>" class="archive-post-thumbnail">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'medium' ); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url( bookory_child_default_featured_image_url() ); ?>"
                                                 alt="<?php echo esc_attr( get_the_title() ); ?>">
                                        <?php endif; ?>
                                    </a>
                                    <div class="archive-post-content">
                                        <h2 class="archive-post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="archive-post-meta">
                                            <span class="archive-post-date"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></span>
                                            <span class="archive-post-categories">
                                                <?php
                                                $category_links = array();
                                                foreach ( get_the_category() as $category ) {
                                                    $content_types = bookory_child_content_types();
                                                    $category_url  = isset( $content_types[ $category->slug ] )
                                                        ? bookory_child_filter_url( array( 'content-type' => $category->slug ) )
                                                        : get_category_link( $category->term_id );
                                                    $category_links[] = sprintf(
                                                        '<a href="%1$s">%2$s</a>',
                                                        esc_url( $category_url ),
                                                        esc_html( $category->name )
                                                    );
                                                }
                                                echo implode( ', ', $category_links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                ?>
                                            </span>
                                        </div>
                                        <div class="archive-post-excerpt">
                                            <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 50, '...' ) ); ?>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="archive-read-more"><?php esc_html_e( 'Read More', 'bookory-child' ); ?></a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    <div class="archive-pagination">
                        <?php
                        the_posts_pagination(
                            array(
                                'mid_size'  => 2,
                                'prev_text' => __( '« Previous', 'bookory-child' ),
                                'next_text' => __( 'Next »', 'bookory-child' ),
                            )
                        );
                        ?>
                    </div>
                <?php else : ?>
                    <div class="archive-no-posts">
                        <h2><?php esc_html_e( 'No posts found', 'bookory-child' ); ?></h2>
                        <p><?php esc_html_e( 'Sorry, but no posts were found for the selected archive.', 'bookory-child' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="right-sidebar">
                <?php bookory_child_render_blog_sidebar(); ?>
            </aside>
        </div>
    </div>
</main>

<?php
bookory_child_render_latest_posts();
get_footer();
