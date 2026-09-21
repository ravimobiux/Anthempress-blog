<div class="blog-filters text-center mb-5">
    <?php
    $categories = get_categories(['hide_empty' => true]);

    echo '<div class="row g-3 justify-content-center">';
    $count = 0;
    foreach ($categories as $category) {
        echo '<div class="col-6 col-md-3">';
        echo '<button class="btn w-100" data-filter=".' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
        echo '</div>';

        $count++;
        if ($count % 4 == 0) {
            echo '<div class="w-100"></div>'; // break row after 4 buttons
        }
    }
    echo '</div>';
    ?>
</div>
