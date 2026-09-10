<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php _e( 'Search Blogs:', 'bookory' ); ?></span>
        <input type="search" class="search-field" 
            placeholder="<?php echo esc_attr_x( 'Search blogs…', 'placeholder', 'bookory' ); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" />
    </label>
    <input type="hidden" name="post_type" value="post" />
    <button type="submit" class="search-submit">
        <i class="fas fa-search"></i>
    </button>
</form>
