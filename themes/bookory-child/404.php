<?php
/**
 * Reference-theme 404 page.
 *
 * @package Bookory_Child
 */

get_header();
?>

<main id="site-content" class="site-main not-found-page">
    <div class="error-404 not-found">
        <div class="error-404-inner">
            <h1>404</h1>
            <h2><?php esc_html_e( 'Page Not Found', 'bookory-child' ); ?></h2>
            <p><?php esc_html_e( 'The page you’re looking for doesn’t exist or has been moved.', 'bookory-child' ); ?></p>
            <div class="error-divider"><span aria-hidden="true">✦</span></div>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-home-link">
                <i class="fas fa-home" aria-hidden="true"></i>
                <?php esc_html_e( 'Go to Home Page', 'bookory-child' ); ?>
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
