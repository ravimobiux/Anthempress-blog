<?php
/**
 * The template for displaying archive pages
 *
 * @package Twenty_Twenty_Five_Child
 */

get_header();

// Get the archive month and year
$archive_year       = get_query_var('year');
$archive_month      = get_query_var('monthnum');
$archive_month_name = $archive_month ? date('F', mktime(0, 0, 0, $archive_month, 1)) : '';
?>

<main id="primary" class="site-main">
    <div class="container alignwide">

        <header class="page-header">
            <?php if ($archive_month && $archive_year) : ?>
                <div class="archive-period">
                    <span class="archive-period-label">Month:</span>
                    <span class="archive-period-value">
                        <?php echo esc_html($archive_month_name . ' ' . $archive_year); ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <div class="archive-content-wrapper">
            <!-- Main Content (75%) -->
            <div class="archive-main-content">
                <?php if (have_posts()) : ?>
                    <div class="archive-posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('archive-post-card'); ?>>
                                <div class="archive-post-inner">
                                    <a href="<?php the_permalink(); ?>" class="archive-post-thumbnail">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium'); ?>
                                        <?php else : ?>
                                            <div class="archive-default-thumbnail">
                                                <h3><?php the_title(); ?></h3>
                                            </div>
                                        <?php endif; ?>
                                    </a>

                                    <div class="archive-post-content">
                                        <h2 class="archive-post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>

                                        <div class="archive-post-meta">
                                            <span class="archive-post-date"><?php echo get_the_date('j F Y'); ?></span>
                                            <span class="archive-post-categories">
                                                <?php
                                                $categories = get_the_category();
                                                if (!empty($categories)) {
                                                    $category_links = array();
                                                    foreach ($categories as $category) {
                                                        $category_links[] = '<a href="https://blog.anthempress.com/?content-type=' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a>';
                                                    }
                                                    echo implode(', ', $category_links);
                                                }
                                                ?>
                                            </span>
                                        </div>

                                        <div class="archive-post-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?>
                                        </div>

                                        <a href="<?php the_permalink(); ?>" class="archive-read-more">Read More</a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <div class="archive-pagination">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => __('&laquo; Previous', 'twentytwentyfive-child'),
                            'next_text' => __('Next &raquo;', 'twentytwentyfive-child'),
                        ));
                        ?>
                    </div>
                <?php else : ?>
                    <div class="archive-no-posts">
                        <h2><?php esc_html_e('No posts found', 'twentytwentyfive-child'); ?></h2>
                        <p><?php esc_html_e('Sorry, but no posts were found for the selected archive.', 'twentytwentyfive-child'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Sidebar (25% width) -->
            <aside class="right-sidebar">

                <!-- Recent Posts -->
                <div class="sidebar-widget recent-posts">
                    <h3 class="widget-title">Recent Posts</h3>
                    <ul>
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 5,
                            'post_status' => 'publish'
                        ));
                        foreach ($recent_posts as $post) :
                        ?>
                            <li><a href="<?php echo get_permalink($post['ID']); ?>"><?php echo esc_html($post['post_title']); ?></a></li>
                        <?php endforeach;
                        wp_reset_query(); ?>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="sidebar-widget categories">
                    <h3 class="widget-title">Categories</h3>
                    <ul>
                        <?php
                        $categories = get_categories();
                        foreach ($categories as $category) {
                            echo '<li><a href="https://blog.anthempress.com/?content-type=' . esc_attr($category->slug) . '">' . esc_html($category->name) . ' <span class="archive-count">(' . intval($category->count) . ')</span></a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- Archives -->
                <div class="sidebar-widget archives">
                    <h3 class="widget-title">Archives</h3>
                    <ul class="archives-list">
                        <?php
                        $archives = wp_get_archives(array(
                            'type'            => 'monthly',
                            'show_post_count' => true,
                            'limit'           => 8,
                            'echo'            => 0
                        ));
                        if ($archives) {
                            $archives = preg_replace('/<\/a>&nbsp;\((\d+)\)/', ' <span class="archive-count">($1)</span></a>', $archives);
                            echo $archives;
                        }
                        ?>
                    </ul>

                    <ul class="archives-list-full" style="display: none;">
                        <?php
                        $all_archives = wp_get_archives(array(
                            'type'            => 'monthly',
                            'show_post_count' => true,
                            'echo'            => 0
                        ));
                        if ($all_archives) {
                            $all_archives = preg_replace('/<\/a>&nbsp;\((\d+)\)/', ' <span class="archive-count">($1)</span></a>', $all_archives);
                            echo $all_archives;
                        }
                        ?>
                    </ul>

                    <button class="view-more-archives">View More</button>
                </div>
            </aside>
        </div>
    </div>
</main>

<!-- Latest Posts Grid -->
<section class="latest-posts-grid">
    <h2 class="latest-posts-title">Latest Posts</h2>
    <div class="latest-posts-container">
        <?php
        $latest_args  = array(
            'post_type'      => 'post',
            'posts_per_page' => 4,
            'post__not_in'   => array(get_the_ID()),
        );
        $latest_query = new WP_Query($latest_args);

        if ($latest_query->have_posts()) :
            while ($latest_query->have_posts()) : $latest_query->the_post(); ?>
                <div class="latest-post-card">
                    <div class="latest-post-inner">
                        <a href="<?php the_permalink(); ?>" class="latest-post-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php else : ?>
                                <div class="default-post-image">
                                    <h3 class="default-post-title"><?php the_title(); ?></h3>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="latest-post-content">
                            <h3 class="latest-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="latest-post-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>
