<div class="blog-filters-system">
<?php
$content_type_buttons = array(
    'interviews' => 'Author Interview',
    'guest-blog' => 'Guest Post',
    'series-spotlight' => 'Series Spotlight',
    'new-release' => 'New Release',
    'anthem-company-news' => 'Company News',
    'media-coverage' => 'Media Coverage',
    'call-for-proposal' => 'Call for Proposal',
    'meet-the-author' => 'Meet the Author',
    'podcast' => 'Podcast & Video',
    'open-access' => 'Open Access',
    'publishing-news' => 'Publishing Industry News',
    'meet-the-series-advisor' => 'Meet the Series Advisor',
    'university-press-blog-roundup' => 'University Press Blog Roundup',
);

// Sanitize and validate content-type
$raw_content_type = isset($_GET['content-type']) ? sanitize_text_field($_GET['content-type']) : '';

if (
    preg_match('/^[a-zA-Z0-9_-]+$/', $raw_content_type) &&
    array_key_exists($raw_content_type, $content_type_buttons)
) {
    $current_content_type = $raw_content_type;
} else {
    $current_content_type = '';
}

// Sanitize subject slug
$current_subject_slug = isset($_GET['subject']) ? sanitize_text_field($_GET['subject']) : '';

if (!preg_match('/^[a-zA-Z0-9_-]+$/', $current_subject_slug)) {
    $current_subject_slug = '';
}

$current_subject = !empty($current_subject_slug)
    ? get_term_by('slug', $current_subject_slug, 'subject')
    : null;

// Pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

if (!is_numeric($paged) || $paged < 1) {
    $paged = 1;
}

// Query args
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'paged'          => $paged,
    'posts_per_page' => 9,
);

// Tax query
$tax_query = array();

if (!empty($current_subject_slug)) {
    $tax_query[] = array(
        'taxonomy' => 'subject',
        'field'    => 'slug',
        'terms'    => $current_subject_slug,
    );
}

if (
    !empty($current_content_type) &&
    array_key_exists($current_content_type, $content_type_buttons)
) {
    $tax_query[] = array(
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => $current_content_type,
    );
}

if (!empty($tax_query)) {
    $args['tax_query'] = count($tax_query) > 1
        ? array_merge(array('relation' => 'AND'), $tax_query)
        : $tax_query;
}

// Run query
$blog_query = new WP_Query($args);

/**
 * Return a real 404 for filtered archive URLs that have no matching posts.
 * The existing "No posts found matching your criteria." UI remains unchanged.
 */
$is_filtered_request = !empty($current_subject_slug) || !empty($current_content_type);

if ($is_filtered_request && !$blog_query->have_posts()) {
    status_header(404);
    nocache_headers();

    // Make WordPress aware that this request is a 404 as well.
    global $wp_query;
    if ($wp_query instanceof WP_Query) {
        $wp_query->set_404();
    }
}

$default_image = twentytwentyfive_child_default_image_url();

// Base URL
$base_url = get_post_type_archive_link('post');

if (empty($base_url)) {
    $base_url = home_url('/');
}

$base_url = trailingslashit($base_url);
?>

<div class="blog-filters-system">

<!-- FILTER INTERFACE -->
<div class="content-type-buttons">
    <div class="buttons-container">

        <?php
        $button_count = count($content_type_buttons);
        $break_points = array(5, 10);

        $i = 1;

        foreach ($content_type_buttons as $slug => $name) :

            $url_params = array(
                'content-type' => $slug,
            );

            if (!empty($current_subject_slug)) {
                $url_params['subject'] = $current_subject_slug;
            }

            $filter_url = add_query_arg($url_params, $base_url);
        ?>

            <a href="<?php echo esc_url($filter_url); ?>"
               class="content-type-button <?php echo $current_content_type === $slug ? 'active' : ''; ?>">
                <?php echo esc_html($name); ?>
            </a>

            <?php if ($i < $button_count && !in_array($i, $break_points)) : ?>
                <span class="button-separator">|</span>
            <?php endif; ?>

            <?php if (in_array($i, $break_points)) : ?>
                <br />
            <?php endif; ?>

        <?php
            $i++;
        endforeach;
        ?>

    </div>
</div>

<!-- MOBILE DROPDOWN -->
<div class="mobile-content-type-dropdown">

    <input type="checkbox" id="content-type-toggle" class="dropdown-toggle">

    <label for="content-type-toggle" class="dropdown-label">

        <?php
        echo !empty($current_content_type) &&
        isset($content_type_buttons[$current_content_type])
            ? esc_html($content_type_buttons[$current_content_type])
            : 'Content Types';
        ?>

        <span class="dropdown-arrow"></span>
    </label>

    <div class="dropdown-content">

        <?php
        $clear_content_type_url = !empty($current_subject_slug)
            ? add_query_arg('subject', $current_subject_slug, $base_url)
            : $base_url;
        ?>

        <a href="<?php echo esc_url($clear_content_type_url); ?>"
           class="<?php echo empty($current_content_type) ? 'active' : ''; ?>">
            Content Types
        </a>

        <?php foreach ($content_type_buttons as $slug => $name) :

            $url_params = array(
                'content-type' => $slug,
            );

            if (!empty($current_subject_slug)) {
                $url_params['subject'] = $current_subject_slug;
            }

            $filter_url = add_query_arg($url_params, $base_url);
        ?>

            <a href="<?php echo esc_url($filter_url); ?>"
               class="<?php echo $current_content_type === $slug ? 'active' : ''; ?>">
                <?php echo esc_html($name); ?>
            </a>

        <?php endforeach; ?>

    </div>
</div>
</div>

<div class="filter-layout">

    <!-- SUBJECT FILTER -->
    <aside class="subject-categories">

        <h3>Subject</h3>

        <!-- DESKTOP -->
        <ul class="desktop-filters">

            <?php
            $subjects = get_terms(array(
                'taxonomy'   => 'subject',
                'hide_empty' => true,
            ));

            if (!empty($subjects) && !is_wp_error($subjects)) :

                foreach ($subjects as $subject) :

                    $active_class = ($current_subject_slug == $subject->slug)
                        ? 'active'
                        : '';

                    $url_params = array(
                        'subject' => $subject->slug,
                    );

                    if (!empty($current_content_type)) {
                        $url_params['content-type'] = $current_content_type;
                    }

                    $filter_url = add_query_arg($url_params, $base_url);
            ?>

                    <li>
                        <a href="<?php echo esc_url($filter_url); ?>"
                           class="<?php echo esc_attr($active_class); ?>">
                            <?php echo esc_html($subject->name); ?>
                        </a>
                    </li>

            <?php
                endforeach;
            endif;
            ?>

        </ul>

        <!-- MOBILE -->
        <div class="mobile-dropdown">

            <input type="checkbox" id="subject-toggle" class="dropdown-toggle">

            <label for="subject-toggle" class="dropdown-label">

                <?php
                echo $current_subject
                    ? esc_html($current_subject->name)
                    : 'Subjects';
                ?>

                <span class="dropdown-arrow"></span>
            </label>

            <div class="dropdown-content">

                <?php
                $clear_subject_url = !empty($current_content_type)
                    ? add_query_arg('content-type', $current_content_type, $base_url)
                    : $base_url;
                ?>

                <a href="<?php echo esc_url($clear_subject_url); ?>"
                   class="<?php echo empty($current_subject_slug) ? 'active' : ''; ?>">
                    Subjects
                </a>

                <?php
                if (!empty($subjects) && !is_wp_error($subjects)) :

                    foreach ($subjects as $subject) :

                        $active_class = ($current_subject_slug == $subject->slug)
                            ? 'active'
                            : '';

                        $url_params = array(
                            'subject' => $subject->slug,
                        );

                        if (!empty($current_content_type)) {
                            $url_params['content-type'] = $current_content_type;
                        }

                        $filter_url = add_query_arg($url_params, $base_url);
                ?>

                        <a href="<?php echo esc_url($filter_url); ?>"
                           class="<?php echo esc_attr($active_class); ?>">
                            <?php echo esc_html($subject->name); ?>
                        </a>

                <?php
                    endforeach;
                endif;
                ?>

            </div>
        </div>
    </aside>

    <!-- POSTS -->
    <main class="posts-grid">

        <?php if (!empty($current_content_type) || !empty($current_subject_slug)) : ?>

            <div class="active-filters">

                <?php if (!empty($current_content_type)) : ?>

                    <span class="active-filter">
                        Content Type:
                        <?php echo esc_html($content_type_buttons[$current_content_type]); ?>

                        <a href="<?php
                            echo !empty($current_subject_slug)
                                ? esc_url(add_query_arg('subject', $current_subject_slug, $base_url))
                                : esc_url($base_url);
                        ?>" class="remove-filter">×</a>
                    </span>

                <?php endif; ?>

                <?php if (!empty($current_subject_slug) && $current_subject) : ?>

                    <span class="active-filter">
                        Subject:
                        <?php echo esc_html($current_subject->name); ?>

                        <a href="<?php
                            echo !empty($current_content_type)
                                ? esc_url(add_query_arg('content-type', $current_content_type, $base_url))
                                : esc_url($base_url);
                        ?>" class="remove-filter">×</a>
                    </span>

                <?php endif; ?>

            </div>

        <?php endif; ?>

        <!-- POSTS LOOP -->
        <?php if ($blog_query->have_posts()) : ?>

            <div class="posts-row">

                <?php while ($blog_query->have_posts()) : $blog_query->the_post();

                    $post_categories = get_the_category();
                    $content_type_label = '';

                    foreach ($post_categories as $category) {
                        if (array_key_exists($category->slug, $content_type_buttons)) {
                            $content_type_label = $content_type_buttons[$category->slug];
                            break;
                        }
                    }
                ?>

                    <article class="post-card">

                        <div class="post-content">

                            <?php if (!empty($content_type_label)) : ?>

                                <div class="content-type-header">
                                    <span class="content-type-label">
                                        <?php echo esc_html($content_type_label); ?>
                                    </span>
                                </div>

                            <?php endif; ?>

                            <h2>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="post-media">

                                <a href="<?php the_permalink(); ?>"
                                   title="<?php echo esc_attr(get_the_title()); ?>">

                                    <?php
                                    if (has_post_thumbnail()) {

                                        the_post_thumbnail('medium', array(
                                            'class' => 'post-image',
                                            'onerror' => 'this.onerror=null; this.src=\'' . esc_url($default_image) . '\'',
                                        ));

                                    } else {

                                        $content = get_the_content();
                                        $first_image = '';

                                        if (preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches)) {

                                            $first_image = $matches[1];

                                            echo '<img src="' . esc_url($first_image) . '" class="post-image" onerror="this.onerror=null; this.src=\'' . esc_url($default_image) . '\'">';

                                        } else {

                                            echo '<img src="' . esc_url($default_image) . '" class="post-image default-post-image">';
                                        }
                                    }
                                    ?>

                                </a>
                            </div>

                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                        </div>
                    </article>

                <?php endwhile; ?>

            </div>

            <!-- PAGINATION -->
            <?php if ($blog_query->max_num_pages > 1) : ?>

                <div class="pagination">

                    <?php
                    $pagination_base = add_query_arg(
                        array_filter(array(
                            'content-type' => $current_content_type,
                            'subject'      => $current_subject_slug,
                        )),
                        $base_url
                    );

                    $pagination_base = trailingslashit($pagination_base);

                    echo paginate_links(array(
                        'base'      => trailingslashit($base_url) . 'page/%#%/',
                        'format'    => '',
                        'current'   => max(1, $paged),
                        'total'     => intval($blog_query->max_num_pages),
                        'mid_size'  => 2,
                        'prev_text' => __('« Previous'),
                        'next_text' => __('Next »'),
                        'add_args'  => false,
                    ));
                    ?>

                </div>

            <?php endif; ?>

        <?php else : ?>

            <p class="no-posts">
                No posts found matching your criteria.
            </p>

        <?php endif; ?>

    </main>
</div>

<?php wp_reset_postdata(); ?>
