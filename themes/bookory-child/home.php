<?php
/**
 * Blog home template with reference-theme filters.
 *
 * @package Bookory_Child
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php get_template_part( 'template-parts/blog-filter-grid' ); ?>
    </div>
</main>

<?php get_footer(); ?>
