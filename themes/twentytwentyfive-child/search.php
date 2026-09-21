<?php
/**
 * Search results template with filters
 */

get_header();

// Get current filter values from URL
$current_content_type = isset($_GET['content-type']) ? sanitize_text_field($_GET['content-type']) : '';
$current_subject_slug = isset($_GET['subject']) ? sanitize_text_field($_GET['subject']) : '';
$current_subject = $current_subject_slug ? get_term_by('slug', $current_subject_slug, 'subject') : null;
$is_subject_archive = ($current_subject !== false && $current_subject !== null);

// Base URL for search (always resets pagination)
$base_url = get_search_link();

// Define available content types
$content_type_buttons = array(
    'interviews' => 'Author Interview',
    'guest-blog' => 'Guest Post',
    'publishing-news' => 'Publishing Industry News',
    'series-spotlight' => 'Series Spotlight',
    'new-release' => 'New Release',
    'university-press-blog-roundup' => 'University Press Blog Roundup',
    'anthem-company-news' => 'Company News',
    'media-coverage' => 'Media Coverage',
    'call-for-proposal' => 'Call for Proposal',
    'featured-author' => 'Featured Author',
    'podcast' => 'Podcast & Video',
    'open-access' => 'Open Access',
);

// Build main search query
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    's' => get_search_query(),
    'paged' => $paged,
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
);

// Add taxonomy filters if set
$tax_query = array();

// Subject filter
if (!empty($current_subject_slug)) {
    $tax_query[] = array(
        'taxonomy' => 'subject',
        'field' => 'slug',
        'terms' => $current_subject_slug,
    );
}

// Content type filter (assuming it's a category)
if (!empty($current_content_type)) {
    $tax_query[] = array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => $current_content_type,
    );
}

if (count($tax_query) > 1) {
    $tax_query['relation'] = 'AND';
}

if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
}

$query = new WP_Query($args);
?>

<main id="site-content" role="main" style="max-width:1125px; margin:0 auto; padding:20px; display:flex; gap:30px; flex-wrap:wrap;">

    <!-- FILTERS -->
    <aside class="filters-column" style="flex:0 0 250px;">

        <!-- Content Type Filter -->
        <h3>Content Type</h3>
        <ul class="desktop-filters" style="padding-left:0px;">
            <li>
                <a href="<?php echo esc_url(remove_query_arg('content-type', $base_url)); ?>" 
                   class="<?php echo empty($current_content_type) ? 'active' : ''; ?>">
                   All
                </a>
            </li>
            <?php foreach ($content_type_buttons as $slug => $name): ?>
                <?php
                $url_params = array('content-type' => $slug);
                if (!empty($current_subject_slug)) {
                    $url_params['subject'] = $current_subject_slug;
                }
                $filter_url = add_query_arg($url_params, $base_url);
                ?>
                <li>
                    <a href="<?php echo esc_url($filter_url); ?>" 
                       class="<?php echo $current_content_type === $slug ? 'active' : ''; ?>">
                        <?php echo esc_html($name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Subject Filter -->
        <h3>Subjects</h3>
        <ul class="desktop-filters" style="padding-left:0px;">
            <li>
                <a href="<?php echo esc_url(remove_query_arg('subject', $base_url)); ?>" 
                   class="<?php echo empty($current_subject_slug) ? 'active' : ''; ?>">
                   All
                </a>
            </li>
            <?php
            $subjects = get_terms(array(
                'taxonomy' => 'subject',
                'hide_empty' => true,
                'orderby' => 'count',
                'order' => 'DESC'
            ));
            
            if (!empty($subjects) && !is_wp_error($subjects)) {
                foreach ($subjects as $subject) {
                    $url_params = array('subject' => $subject->slug);
                    if (!empty($current_content_type)) {
                        $url_params['content-type'] = $current_content_type;
                    }
                    $filter_url = add_query_arg($url_params, $base_url);
                    $active_class = ($current_subject_slug === $subject->slug) ? 'active' : '';
                    echo '<li><a href="' . esc_url($filter_url) . '" class="' . esc_attr($active_class) . '">' . esc_html($subject->name) . '</a></li>';
                }
            }
            ?>
        </ul>

    </aside>

    <!-- RESULTS -->
    <div class="results-column" style="flex:1; min-width:300px;">

        <header class="search-header">
            <h3 class="search-title">
                <?php 
                printf(
                    __('Search Results for: %s', 'textdomain'), 
                    '<span>' . get_search_query() . '</span>'
                );
                
                if (!empty($current_content_type) || !empty($current_subject_slug)) {
                    echo '<span class="filter-indicator"> (Filtered)</span>';
                }
                ?>
            </h3>
            
            <?php if (!empty($current_content_type) || !empty($current_subject_slug)): ?>
                <div class="active-filters">
                    <?php if (!empty($current_content_type)): ?>
                        <?php
                        $clear_url = !empty($current_subject_slug) 
                            ? add_query_arg('subject', $current_subject_slug, $base_url)
                            : $base_url;
                        ?>
                        <span class="active-filter">
                            Content Type: <?php echo esc_html($content_type_buttons[$current_content_type]); ?>
                            <a href="<?php echo esc_url($clear_url); ?>" class="remove-filter">×</a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($current_subject_slug)): ?>
                        <?php
                        $clear_url = !empty($current_content_type) 
                            ? add_query_arg('content-type', $current_content_type, $base_url)
                            : $base_url;
                        ?>
                        <span class="active-filter">
                            Subject: <?php echo esc_html($current_subject->name); ?>
                            <a href="<?php echo esc_url($clear_url); ?>" class="remove-filter">×</a>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if ($query->have_posts()) : ?>
            <div class="search-results-list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?>>
                        <div class="search-result-inner">
                            <!-- Image -->
                            <a href="<?php the_permalink(); ?>" class="search-result-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    <img
                                        src="<?php echo esc_url(twentytwentyfive_child_default_image_url()); ?>"
                                        class="search-default-thumbnail"
                                        alt="<?php echo esc_attr(get_the_title()); ?>"
                                        loading="lazy">
                                <?php endif; ?>
                            </a>
                            
                            <div class="search-result-content">
                                <!-- Title -->
                                <h2 class="search-result-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <!-- Meta -->
                                <div class="search-result-meta">
                                    <span class="search-result-date"><?php echo get_the_date('j F Y'); ?></span>
                                </div>

                                <!-- Excerpt -->
                                <div class="search-result-excerpt">
                                    <?php 
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                        $excerpt = wp_trim_words(get_the_content(), 30);
                                    }
                                    echo esc_html($excerpt) . '...'; 
                                    ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="search-read-more">Read More</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $query->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'mid_size' => 2,
                    'prev_text' => __('« Previous'),
                    'next_text' => __('Next »'),
                    'add_args' => array_filter(array(
                        'content-type' => $current_content_type,
                        'subject' => $current_subject_slug
                    ))
                ));
                ?>
            </div>

        <?php else : ?>
            <div class="no-results">
                <h3><?php _e('Nothing Found', 'textdomain'); ?></h3>
                <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'textdomain'); ?></p>
                <p>Or <a href="<?php echo esc_url($base_url); ?>">clear all filters</a> to see all results.</p>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>
