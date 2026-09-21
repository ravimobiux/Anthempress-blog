<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
        /**
         * Functions hooked in to bookory_single_post_top action
         * @see bookory_post_thumbnail - 10
         */
        do_action('bookory_single_post_top');
    ?>
    <div class="single-content">
        <?php

        /**
         * Functions hooked in to bookory_single_post action
         *
         * @see bookory_post_header         - 20
         * @see bookory_post_content         - 30
         */
        do_action('bookory_single_post');


        /**
         * Functions hooked in to bookory_single_post_bottom action
         *
         * @see bookory_post_taxonomy      - 5
         * @see bookory_display_comments    - 20
         */
        remove_action( 'bookory_single_post_bottom', 'bookory_post_taxonomy', 5 );
        remove_action( 'bookory_single_post_bottom', 'bookory_post_nav', 10 );
        do_action('bookory_single_post_bottom');
        ?>

    </div>

    <?php if ( function_exists( 'bookory_child_post_nav' ) ) : ?>
        <div class="bookory-child-wide-post-navigation">
            <?php bookory_child_post_nav(); ?>
        </div>
    <?php endif; ?>

</article><!-- #post-## -->
