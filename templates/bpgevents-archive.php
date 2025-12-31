<?php
/**
 * Archive Template for bpge_event
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<div class="bpgevents-archive">

    <h1 class="bpgevents-archive-title">
        <?php _e( 'Events', 'bpgevents' ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>

        <ul class="bpgevents-events-ul">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php
                $event_id   = get_the_ID();
                $is_virtual = get_post_meta( $event_id, 'bpge_is_virtual', true );
                $city       = get_post_meta( $event_id, 'bpge_city', true );
                ?>

                <li class="bpgevents-event-item">

                    <a class="bpgevents-event-title" href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>

                    <div class="bpgevents-event-meta">

                        <?php if ( $is_virtual ) : ?>
                            <span class="bpgevents-tag bpgevents-tag-virtual">
                                <?php _e( 'Virtual', 'bpgevents' ); ?>
                            </span>
                        <?php else : ?>
                            <span class="bpgevents-tag bpgevents-tag-presential">
                                <?php _e( 'Presential', 'bpgevents' ); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $city ) : ?>
                            <span class="bpgevents-city">
                                <?php echo esc_html( $city ); ?>
                            </span>
                        <?php endif; ?>

                    </div>

                </li>

            <?php endwhile; ?>

        </ul>

        <div class="bpgevents-pagination">
            <?php the_posts_pagination(); ?>
        </div>

    <?php else : ?>

        <p><?php _e( 'No events found.', 'bpgevents' ); ?></p>

    <?php endif; ?>

</div>

<?php
get_footer();
