<?php
/**
 * The template for displaying single posts
 * @package Twenty_Twenty_Five_Child
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="post-content-container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <span class="post-date">
                        <?php echo get_the_date('j F Y'); ?>
                    </span>
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>
                
                <div class="entry-content-wrapper">
                    <!-- Left Sidebar (25% width) -->
                    <aside class="left-sidebar">
                        <?php twentytwentyfive_child_post_sidebar(); ?>
                    </aside>
                    
                    <!-- Main Content (50% width) -->
                    <div class="post-main-content">
                        <div class="entry-content">
                            <?php
                            $post_content = apply_filters("the_content", get_the_content());

                            // Hide broken inline images and clean up their empty HTML wrappers.
                            $post_content = preg_replace_callback(
                                '/<img\b[^>]*>/i',
                                static function ($matches) {
                                    $image_tag = $matches[0];

                                    if (stripos($image_tag, 'onerror=') !== false) {
                                        return $image_tag;
                                    }

                                    $onerror = 'this.onerror=null;var image=this;var figure=image.closest("figure");if(figure){figure.remove();return false;}var parent=image.parentElement;image.remove();while(parent&&parent.classList&&!parent.classList.contains("entry-content")){var tag=parent.tagName;var hasContent=parent.textContent.trim()||parent.querySelector("img,video,iframe,table,ul,ol,figure");if(["A","P","DIV"].indexOf(tag)!==-1&&!hasContent){var next=parent.parentElement;parent.remove();parent=next;}else{break;}}return false;';

                                    return preg_replace(
                                        '/\s*(\/?)>$/',
                                        ' onerror="' . esc_attr($onerror) . '"$1>',
                                        $image_tag
                                    );
                                },
                                $post_content
                            );

                            echo $post_content;
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'twentytwentyfive-child'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                    </div>
                    
                    <!-- Right Sidebar (25% width) -->
                    <aside class="right-sidebar">
                        <!-- Recent Posts (5 titles) -->
                        <div class="sidebar-widget recent-posts">
                            <h3 class="widget-title">Recent Posts</h3>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish',
                                    'post__not_in' => array(get_the_ID())
                                ));
                                foreach($recent_posts as $post) : ?>
                                    <li><a href="<?php echo get_permalink($post['ID']); ?>"><?php echo $post['post_title']; ?></a></li>
                                <?php endforeach; wp_reset_query(); ?>
                            </ul>
                        </div>
                        
                        <!-- Category Filters -->
                        <div class="sidebar-widget categories">
                            <h3 class="widget-title">Categories</h3>
                            <ul>
                                <?php
                                $categories = get_categories();
                                foreach($categories as $category) {
                                    echo '<li><a href="https://blog.anthempress.com/?content-type=' . $category->slug . '">' . $category->name . ' (' . $category->count . ')</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        
<!-- Archives -->
<div class="sidebar-widget archives">
    <h3 class="widget-title">Archives</h3>
    <ul class="archives-list">
        <?php 
        // Get limited archives (8 items)
        $archives = wp_get_archives(array(
            'type' => 'monthly',
            'show_post_count' => true,
            'limit' => 8,
            'echo' => 0
        ));
        
        // Process the archives to format them properly
        if ($archives) {
            $archives = preg_replace('/<\/a>&nbsp;\((\d+)\)/', ' <span class="post-count">($1)</span></a>', $archives);
            echo $archives;
        }
        ?>
    </ul>
    <ul class="archives-list-full" style="display: none;">
        <?php 
        // Get all archives (hidden initially)
        $all_archives = wp_get_archives(array(
            'type' => 'monthly',
            'show_post_count' => true,
            'echo' => 0
        ));
        
        // Process the archives to format them properly
        if ($all_archives) {
            $all_archives = preg_replace('/<\/a>&nbsp;\((\d+)\)/', ' <span class="post-count">($1)</span></a>', $all_archives);
            echo $all_archives;
        }
        ?>
    </ul>
    <button class="view-more-archives">View More</button>
</div>                
            </article>
            
<?php
// Custom post navigation with fixed class name
$prev_post = get_previous_post();
$next_post = get_next_post();
?>

<?php if ($prev_post || $next_post) : ?>
    <nav class="post-navigation" role="navigation" aria-label="<?php esc_attr_e('Post navigation', 'twentytwentyfive-child'); ?>">
        <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'twentytwentyfive-child'); ?></h2>
        <div class="post-nav-links"> <!-- Changed from nav-links to post-nav-links -->
            <?php if ($prev_post) : ?>
                <div class="nav-previous">
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev" data-tooltip="<?php echo esc_attr($prev_post->post_title); ?>">
                        <span class="nav-subtitle"><?php esc_html_e('Previous Post:', 'twentytwentyfive-child'); ?></span>
                        <span class="nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($next_post) : ?>
                <div class="nav-next">
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next" data-tooltip="<?php echo esc_attr($next_post->post_title); ?>">
                        <span class="nav-subtitle"><?php esc_html_e('Next Post:', 'twentytwentyfive-child'); ?></span>
                        <span class="nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>
            
        <?php endwhile; ?>
    </div>
</main>
<!-- Latest Posts Grid -->
<section class="latest-posts-grid">
    <h2 class="latest-posts-title">Latest Posts</h2>
<div class="latest-posts-container">
    <?php
    $latest_args = array(
        'post_type' => 'post',
        'posts_per_page' => 4,
        'post__not_in' => array(get_the_ID()),
    );
    $latest_query = new WP_Query($latest_args);

    if ($latest_query->have_posts()) :
        while ($latest_query->have_posts()) : $latest_query->the_post(); ?>
            <div class="latest-post-card">
                <a href="<?php the_permalink(); ?>" class="latest-post-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php
                        the_post_thumbnail('medium', array(
                            'onerror' => 'this.onerror=null;this.src=\'' . esc_url(twentytwentyfive_child_default_image_url()) . '\'',
                        ));
                        ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(twentytwentyfive_child_default_image_url()); ?>"
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             loading="lazy">
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
        <?php endwhile;
        wp_reset_postdata();
    endif;
    ?>
</div>
</section>

<?php 
get_footer();
