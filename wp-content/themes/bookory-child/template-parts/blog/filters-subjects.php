<?php
$subjects = get_terms([
    'taxonomy'   => 'subject',
    'hide_empty' => true,
]);

if (!empty($subjects) && !is_wp_error($subjects)) : ?>
<h6 class="mb-2">Subjects</h6>
    <ul class="subject-filters">
        <?php foreach ($subjects as $subject) : ?>
            <li>
                <a href="<?php echo esc_url(get_term_link($subject)); ?>">
                    <?php echo esc_html($subject->name); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
