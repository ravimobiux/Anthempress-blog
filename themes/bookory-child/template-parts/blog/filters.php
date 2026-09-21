<div class="blog-filters text-center mb-5">
    <?php
    $categories = get_categories(['hide_empty' => true]);

    echo '<div class="filter-buttons">';
    echo '<button class="btn active" data-filter="*">All</button>';
    foreach ($categories as $category) {
        echo '<button class="btn" data-filter=".' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
    }
    echo '</div>';
    ?>
</div>
