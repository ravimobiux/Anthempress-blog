<div class="column-item post-style-2">
    <div class="post-inner">

        <!-- 1. Content Type Label -->
        <?php
        $content_type = get_the_terms(get_the_ID(), 'content_type');
        if ($content_type && !is_wp_error($content_type)) {
            echo '<span class="post-label badge bg-primary mb-2">' . esc_html($content_type[0]->name) . '</span>';
        } else {
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<span class="post-label badge bg-secondary mb-2">' . esc_html($categories[0]->name) . '</span>';
            }
        }
        ?>

        <!-- 2. Title -->
        <?php the_title(
            '<h3 class="entry-title mb-3"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', 
            '</a></h3>'
        ); ?>

        <!-- 3. Featured Image -->
        <div class="post-thumbnail mb-3">
            <?php bookory_post_thumbnail('bookory-post-grid', false); ?>
        </div>

        <!-- 4. Excerpt -->
        <div class="entry-content">
            <p><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
        </div>

    </div>
</div>
