<?php
/**
 * Blog pagination
 *
 * @package Bookory Child
 */

global $wp_query;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if ($wp_query->max_num_pages > 1) {
    echo '<div class="pagination-wrap">';
    echo paginate_links([
        'total'   => $wp_query->max_num_pages,
        'current' => $paged,
        'prev_text' => __('« Prev', 'bookory-child'),
        'next_text' => __('Next »', 'bookory-child'),
    ]);
    echo '</div>';
}
