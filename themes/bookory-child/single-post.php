<?php

get_header(); ?>

	<div class="single-post-layout">
		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'bookory_single_post_before' );

			get_template_part( 'content', 'single' );

			do_action( 'bookory_single_post_after' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
		</div><!-- #primary -->

		<aside class="single-post-sidebar" aria-label="Blog sidebar">
			<?php
			if ( function_exists( "bookory_child_render_blog_sidebar" ) ) {
				bookory_child_render_blog_sidebar( get_the_ID() );
			}
			?>
		</aside>
	</div><!-- .single-post-layout -->

<?php
if ( function_exists( 'bookory_child_render_latest_posts' ) ) {
    bookory_child_render_latest_posts( get_the_ID() );
}

get_footer();
