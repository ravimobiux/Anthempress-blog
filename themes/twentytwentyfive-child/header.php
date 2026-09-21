<?php
/**
 * The header template file
 *
 * @package Twenty_Twenty_Five_Child
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?php
    /**
     * Fix soft 404 for empty subject filters
     * Check if subject parameter exists and has no posts
     */
    if (isset($_GET['subject']) && !empty($_GET['subject'])) {
        $subject_slug = sanitize_text_field($_GET['subject']);
        
        // Check if any posts exist for this subject
        // Adjust the meta_key if your subjects use a different custom field
        $has_posts = new WP_Query([
            'meta_key' => 'subject', // Change this to your actual meta key if different
            'meta_value' => $subject_slug,
            'posts_per_page' => 1,
            'fields' => 'ids',
        ]);
        
        // If no posts found, set 404 status
        if (empty($has_posts->posts)) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            
            // Add noindex as additional precaution
            echo '<meta name="robots" content="noindex, follow">' . "\n";
        }
        wp_reset_postdata();
    }
    ?>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'twentytwentyfive'); ?></a>

    <?php twentytwentyfive_child_custom_header(); ?>
  <?php
    wp_nav_menu([
      'theme_location' => 'primary',
      'container' => false,
      'menu_class' => 'mobile-menu-items',
    ]);
  ?>
</div>
    <div id="content" class="site-content">
