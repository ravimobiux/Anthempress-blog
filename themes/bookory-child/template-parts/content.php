<?php
/**
 * Template part for displaying posts in grid (blog home & archives)
 *
 * @package Bookory Child
 */

// Get the first category
$categories = get_the_category();
$category_label = '';
if (!empty($categories)) {
    $category_label = $categories[0]->name;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>
    <div class="blog-card-inner">

        <!-- 1. Category Label -->
        <?php if ($category_label): ?>
            <span class="content-type-label">
                <?php echo esc_html($category_label); ?>
            </span>
        <?php endif; ?>

        <!-- 2. Post Title -->
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>

        <!-- 3. Featured Image -->
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php 
                if (has_post_thumbnail()) {
                    the_post_thumbnail('bookory-post-grid');
                } else {
                    // fallback image
                    echo '<img src="' . esc_url(get_stylesheet_directory_uri() . '/assets/images/default-thumb.jpg') . '" alt="' . esc_attr(get_the_title()) . '" />';
                }
                ?>
            </a>
        </div>

        <!-- 4. Post Excerpt -->
        <div class="entry-summary">
		 <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
        </div>

    </div>
</article>
