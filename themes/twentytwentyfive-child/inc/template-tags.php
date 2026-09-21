<?php
/**
 * Custom template tags for Twenty Twenty-Five Child Theme
 */

if (!function_exists('twentytwentyfive_child_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function twentytwentyfive_child_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        echo '<span class="posted-on">' . $time_string . '</span>';
    }
endif;

if (!function_exists('twentytwentyfive_child_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function twentytwentyfive_child_posted_by() {
        printf(
            /* translators: %s: Author name. */
            esc_html__('By %s', 'twentytwentyfive-child'),
            '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
        );
    }
endif;

if (!function_exists('twentytwentyfive_child_post_sidebar')) :
    /**
     * Displays the post sidebar with cover image
     */
    function twentytwentyfive_child_post_sidebar() {
        $default_image = 'https://blog.anthempress.com/wp-content/uploads/2025/08/Default-Anthem-Press.jpeg';
        ?>
        <aside class="post-sidebar">
            
	<?php 
// Get default image path
$default_image = 'https://blog.anthempress.com/wp-content/uploads/2025/08/Default-Anthem-Press.jpeg';

// Display cover image from ACF field if available
$cover_image = get_field('cover_image');
if ($cover_image) : 
    // Get image data based on return format (array or ID)
    $image_data = is_array($cover_image) ? $cover_image : wp_get_attachment_metadata($cover_image);
    
    // Get optimal image size
    $image_url = is_array($cover_image) ? $cover_image['url'] : wp_get_attachment_image_url($cover_image, 'large');
    $image_alt = is_array($cover_image) ? ($cover_image['alt'] ?: get_the_title()) : 
                 (get_post_meta($cover_image, '_wp_attachment_image_alt', true) ?: get_the_title());
    
    if ($image_url) : ?>
        <div class="post-featured-image">
            <img src="<?php echo esc_url($image_url); ?>" 
                 srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($cover_image, 'large')); ?>"
                 sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 800px"
                 alt="<?php echo esc_attr($image_alt); ?>" 
                 class="featured-image" 
                 loading="lazy"
                 width="<?php echo esc_attr($image_data['width'] ?? 800); ?>"
                 height="<?php echo esc_attr($image_data['height'] ?? 600); ?>">
        </div>
    <?php endif;

// Fallback to featured image
elseif (has_post_thumbnail()) : 
    $thumbnail_id = get_post_thumbnail_id();
    $image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) ?: get_the_title();
    ?>
    <div class="post-featured-image">
        <?php echo wp_get_attachment_image(
            $thumbnail_id,
            'large',
            false,
            array(
                'class' => 'featured-image',
                'alt' => esc_attr($image_alt),
                'loading' => 'lazy',
                'srcset' => wp_get_attachment_image_srcset($thumbnail_id, 'large'),
                'sizes' => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 800px'
            )
        ); ?>
    </div>

<!-- Final fallback to default image -->
<?php else : ?>
    <div class="post-featured-image">
        <img src="<?php echo esc_url($default_image); ?>" 
             alt="<?php esc_attr_e('Default post image', 'twentytwentyfive-child'); ?>" 
             class="featured-image" 
             loading="lazy"
             width="800"
             height="600">
    </div>
<?php endif; ?>
            
        </aside>
        <?php
    }
endif;

if (!function_exists('twentytwentyfive_child_entry_header')) :
    /**
     * Displays the entry header with title and publish date
     */
    function twentytwentyfive_child_entry_header() {
        ?>
        <header class="entry-header">
            <span class="post-date">
                <?php echo get_the_date(); ?>
            </span>
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>
        <?php
    }
endif;
