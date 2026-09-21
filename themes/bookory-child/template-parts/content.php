<?php
/**
 * Bookory loop card override.
 *
 * @package Bookory_Child
 */

$image_url = bookory_child_default_featured_image_url();
$label     = bookory_child_post_content_type_label();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
    <div class="post-content">
        <?php if ( $label ) : ?>
            <div class="content-type-header">
                <span class="content-type-label"><?php echo esc_html( $label ); ?></span>
            </div>
        <?php endif; ?>

        <h2>
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>

        <div class="post-media">
            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium', array( 'class' => 'post-image' ) ); ?>
                <?php elseif ( $image_url ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" class="post-image" alt="<?php echo esc_attr( get_the_title() ); ?>">
                <?php else : ?>
                    <div class="default-post-image"><span><?php the_title(); ?></span></div>
                <?php endif; ?>
            </a>
        </div>

        <div class="post-excerpt">
            <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?>
        </div>
    </div>
</article>
