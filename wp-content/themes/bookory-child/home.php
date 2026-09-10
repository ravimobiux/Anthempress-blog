<?php
/**
 * Template for Blog Home (Latest Posts)
 *
 * @package Bookory Child
 */

get_header();
?>

<div class="container my-5">

    <!-- Category Filters on Top -->
    <?php get_template_part('template-parts/blog/filters-categories'); ?>

    <!-- Active Filters -->
    <?php get_template_part('template-parts/blog/filters-active'); ?>

    <div class="row">
        <!-- Sidebar with Subject Filters (25%) -->
        <div class="col-md-3 col-12">
            <?php get_template_part('template-parts/blog/filters-subjects'); ?>
        </div>

        <!-- Blog Grid (75%) -->
	<div class="col-md-9 col-12">
    		<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        	<?php
        	if (have_posts()) :
            	while (have_posts()) : the_post();
                	get_template_part('template-parts/content');
            	endwhile;
        	else :
            	echo '<p>' . esc_html__('No posts found.', 'bookory-child') . '</p>';
        	endif;
        	?>
    		</div>

    	<!-- Pagination BELOW the grid -->
    	<?php get_template_part('template-parts/blog/pagination'); ?>
	</div>

    </div>
</div>

<?php get_footer(); ?>
