<?php
/**
 * The main template file
 *
 * @package Twenty Twenty-Five Child
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Load the filter grid template part
        $template_path = locate_template('template-parts/blog-filter-grid.php');

        if (!empty($template_path)) {
            get_template_part('template-parts/blog-filter-grid');
        } else {
            // Fallback content if template part is missing
            echo '<div class="error-notice" style="padding: 20px; background: #ffeeee; border: 1px solid #ffcccc;">';
            echo '<p>Blog filter template not found. Showing default posts loop.</p>';
            echo '</div>';

            if (have_posts()) :
                echo '<div class="default-posts-listing">';
                while (have_posts()) : the_post();
                    get_template_part('template-parts/content', get_post_type());
                endwhile;


                the_posts_pagination(array(
                    'prev_text' => __('« Previous'),
                    'next_text' => __('Next »'),
                ));
                echo '</div>';
            else :
                get_template_part('template-parts/content', 'none');
            endif;
        }
        ?>
    </div>
</main>

<?php 
get_footer();
