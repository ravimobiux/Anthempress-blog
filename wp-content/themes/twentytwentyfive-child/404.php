<?php
/**
 * The template for displaying 404 pages (Not Found)
 * @package Twenty Twenty-Five Child
 */

get_header();
?>

<main id="site-content" class="site-main not-found-page">
    <div class="error-404 not-found" style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
        
        <div style="text-align: center; max-width: 600px; width: 100%;">
            
            <!-- Error Code -->
            <h1 style="font-size: 6rem; font-weight: 800; margin: 0; line-height: 1; background: linear-gradient(135deg, #044D53, #0A7B83); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                404
            </h1>

            <!-- Message -->
            <h2 style="font-size: 2rem; font-weight: 700; margin: 1rem 0 0.5rem; color: #1a1a1a;">
                <?php esc_html_e( 'Page Not Found', 'twentytwentyfive-child' ); ?>
            </h2>

            <p style="font-size: 1.1rem; margin: 0.5rem 0 2rem; color: #666; line-height: 1.6;">
                <?php esc_html_e( 'The page you\'re looking for doesn\'t exist or has been moved.', 'twentytwentyfive-child' ); ?>
            </p>

            <!-- Decorative Divider -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
                <div style="width: 60px; height: 2px; background: #B2DFE2;"></div>
                <span style="color: #0A7B83; font-size: 1.5rem;">✦</span>
                <div style="width: 60px; height: 2px; background: #B2DFE2;"></div>
            </div>

            <!-- Home Page Link -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
               style="display: inline-flex; align-items: center; gap: 0.5rem; background: #044D53; color: #fff; padding: 0.75rem 2rem; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(4, 77, 83, 0.2);"
               onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 12px rgba(4, 77, 83, 0.3)';"
               onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(4, 77, 83, 0.2)';">
                <svg style="width: 20px; height: 20px; transition: transform 0.3s ease;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <?php esc_html_e( 'Go to Home Page', 'twentytwentyfive-child' ); ?>
            </a>

        </div>
    </div>

    <!-- Decorative Bottom Bar -->
    <div style="position: fixed; bottom: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #B2DFE2, #044D53, #B2DFE2);"></div>

</main>

<?php
get_footer();
