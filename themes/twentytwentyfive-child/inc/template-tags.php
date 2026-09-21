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
        $default_image = twentytwentyfive_child_default_image_url();
        ?>
        <aside class="post-sidebar">
            
	<?php 
// Select a cover image, then fall back to the featured image and default image.
$default_image = twentytwentyfive_child_default_image_url();
$cover_image = function_exists("get_field") ? get_field("cover_image") : null;
$image_url = "";
$image_srcset = "";
$image_alt = get_the_title();
$image_width = 800;
$image_height = 600;
$fallback_image = $default_image;

// ACF image fields may return an array, attachment ID, or URL.
if (is_array($cover_image)) {
    $image_url = !empty($cover_image["url"]) ? $cover_image["url"] : "";
    $image_alt = !empty($cover_image["alt"]) ? $cover_image["alt"] : $image_alt;
    $image_width = !empty($cover_image["width"]) ? $cover_image["width"] : $image_width;
    $image_height = !empty($cover_image["height"]) ? $cover_image["height"] : $image_height;

    if (!empty($cover_image["ID"])) {
        $image_srcset = wp_get_attachment_image_srcset((int) $cover_image["ID"], "large") ?: "";
    }
} elseif (is_numeric($cover_image)) {
    $cover_id = (int) $cover_image;
    $image_url = wp_get_attachment_image_url($cover_id, "large") ?: "";
    $image_srcset = wp_get_attachment_image_srcset($cover_id, "large") ?: "";
    $image_metadata = wp_get_attachment_metadata($cover_id);
    $image_alt = get_post_meta($cover_id, "_wp_attachment_image_alt", true) ?: $image_alt;
    $image_width = $image_metadata["width"] ?? $image_width;
    $image_height = $image_metadata["height"] ?? $image_height;
} elseif (is_string($cover_image) && filter_var($cover_image, FILTER_VALIDATE_URL)) {
    $image_url = $cover_image;
}

// If there is no usable cover-image value, use the WordPress featured image.
if (has_post_thumbnail()) {
    $thumbnail_id = get_post_thumbnail_id();
    $featured_image = wp_get_attachment_image_url($thumbnail_id, "large");

    if ($image_url) {
        // A broken remote cover image can fall back to the featured image in the browser.
        $fallback_image = $featured_image ?: $default_image;
    } elseif ($featured_image) {
        $image_url = $featured_image;
        $image_srcset = wp_get_attachment_image_srcset($thumbnail_id, "large") ?: "";
        $image_alt = get_post_meta($thumbnail_id, "_wp_attachment_image_alt", true) ?: $image_alt;
        $image_metadata = wp_get_attachment_metadata($thumbnail_id);
        $image_width = $image_metadata["width"] ?? $image_width;
        $image_height = $image_metadata["height"] ?? $image_height;
    }
}

if (!$image_url) {
    $image_url = $default_image;
    $image_alt = __("Default post image", "twentytwentyfive-child");
}

$image_error_handler = "this.onerror=null;this.removeAttribute(\"srcset\");this.removeAttribute(\"sizes\");this.src=" . chr(34) . esc_url($fallback_image) . chr(34) . ";";
?>
        <div class="post-featured-image">
            <img src="<?php echo esc_url($image_url); ?>"
                 <?php if ($image_srcset) : ?>srcset="<?php echo esc_attr($image_srcset); ?>"<?php endif; ?>
                 sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 800px"
                 alt="<?php echo esc_attr($image_alt); ?>"
                 class="featured-image"
                 loading="lazy"
                 onerror="<?php echo esc_attr($image_error_handler); ?>"
                 width="<?php echo esc_attr($image_width); ?>"
                 height="<?php echo esc_attr($image_height); ?>">
        </div>

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
