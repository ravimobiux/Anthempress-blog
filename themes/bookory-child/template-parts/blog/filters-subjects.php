<?php
$subjects = get_terms(
    array(
        'taxonomy'   => 'subject',
        'hide_empty' => true,
    )
);

if ( ! empty( $subjects ) && ! is_wp_error( $subjects ) ) :
    $current_subject = bookory_child_filter_value( 'subject' );
    $current_type    = bookory_child_filter_value( 'content-type' );
    ?>
    <div class="subject-categories">
        <h3><?php esc_html_e( 'Subject', 'bookory-child' ); ?></h3>
        <ul class="desktop-filters">
            <?php foreach ( $subjects as $subject ) : ?>
                <?php
                $args = array( 'subject' => $subject->slug );
                if ( $current_type ) {
                    $args['content-type'] = $current_type;
                }
                ?>
                <li>
                    <a href="<?php echo esc_url( bookory_child_filter_url( $args ) ); ?>"
                       class="<?php echo esc_attr( $current_subject === $subject->slug ? 'active' : '' ); ?>">
                        <?php echo esc_html( $subject->name ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
