<?php
/**
 * Bookory child footer.
 *
 * @package Bookory_Child
 */
?>
    </div>
</div>

<footer id="site-footer" class="anthem-footer">
    <div class="footer-container">
        <div class="footer-columns">
            <?php
            $footer_menu = array(
                array(
                    array( 'title' => 'WORK WITH US', 'link' => 'https://anthempress.com/careers/' ),
                    array( 'title' => 'OPEN ACCESS', 'link' => 'https://anthempress.com/authors-hub?section=open-access' ),
                    array( 'title' => 'RIGHTS & PERMISSIONS', 'link' => 'https://anthempress.com/rights-and-permissions/' ),
                    array( 'title' => 'PRIVACY & COOKIES POLICY', 'link' => 'https://anthempress.com/privacy-policy/' ),
                    array( 'title' => 'TERMS & CONDITIONS', 'link' => 'https://anthempress.com/terms-conditions/' ),
                    array( 'title' => 'ACCESSIBILITY', 'link' => 'https://anthempress.com/accessibility/' ),
                ),
                array(
                    array( 'title' => 'CATALOGUES', 'link' => 'https://anthempress.com/catalogues/' ),
                    array( 'title' => 'BOOKSELLERS', 'link' => 'https://anthempress.com/for-book-sellers/' ),
                    array( 'title' => 'LIBRARIANS', 'link' => 'https://anthempress.com/for-librarians/' ),
                    array( 'title' => 'REVIEWERS', 'link' => 'https://anthempress.com/for-reviewers/' ),
                    array( 'title' => 'INSTRUCTORS', 'link' => 'https://anthempress.com/for-instructors/' ),
                    array( 'title' => 'PARTNERSHIP PUBLISHING', 'link' => 'https://anthempress.com/partnerships/' ),
                ),
                array(
                    array( 'title' => 'SALES REPRESENTATION', 'link' => 'https://anthempress.com/sales-representation/' ),
                    array( 'title' => 'ORDERING EBOOKS', 'link' => 'https://anthempress.com/ordering-ebooks/' ),
                    array( 'title' => 'SHIPPING: NORTH AMERICA', 'link' => 'https://anthempress.com/ordering-within-north-america/' ),
                    array( 'title' => 'Shipping: UK, EU & ROW', 'link' => 'https://anthempress.com/ordering-within-the-united-kingdom-europe-and-the-rest-of-the-world/' ),
                    array( 'title' => 'Shipping: Australia & NZ', 'link' => 'https://anthempress.com/ordering-within-the-australia-and-new-zealand/' ),
                ),
            );

            foreach ( $footer_menu as $menu_column ) :
                ?>
                <div class="footer-column">
                    <?php foreach ( $menu_column as $menu_item ) : ?>
                        <a href="<?php echo esc_url( $menu_item['link'] ); ?>" class="footer-link">
                            <?php echo esc_html( $menu_item['title'] ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-container">
        <p class="footer-copyright">
            <?php
            printf(
                esc_html__( 'Copyright © %1$s Anthem Press. Registered in England & Wales under No. 02889958.', 'bookory-child' ),
                esc_html( wp_date( 'Y' ) )
            );
            ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
