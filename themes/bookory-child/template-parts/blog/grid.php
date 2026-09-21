<?php
/**
 * Blog grid loop
 *
 * @package Bookory Child
 */

echo '<!-- CHILD THEME GRID LOADED -->';

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
);

$query = new WP_Query($args);

if ($query->have_posts()) : ?>

    <!-- ✅ GRID WRAPPER -->
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php get_template_part('template-parts/content'); ?>
        <?php endwhile; ?>
    <!-- /.blog-grid -->

<?php
else :
    echo '<p>' . esc_html__('No posts found.', 'bookory-child') . '</p>';
endif;

wp_reset_postdata();
