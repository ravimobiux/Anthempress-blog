<form role="search" method="get" class="nav-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <input type="search" class="search-field" 
               placeholder="<?php esc_attr_e('Search blog posts...', 'twentytwentyfive-child'); ?>" 
               value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'twentytwentyfive-child'); ?>">
        <i class="fa fa-search"></i>
    </button>
</form>
