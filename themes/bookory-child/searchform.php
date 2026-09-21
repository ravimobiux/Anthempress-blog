<form role="search" method="get" class="nav-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e( 'Search blog posts', 'bookory-child' ); ?></span>
        <input type="search" class="search-field"
               placeholder="<?php esc_attr_e( 'Search blog posts...', 'bookory-child' ); ?>"
               value="<?php echo esc_attr( get_search_query() ); ?>"
               name="s">
    </label>
    <button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'bookory-child' ); ?>">
        <i class="fas fa-search" aria-hidden="true"></i>
    </button>
</form>
