<?php
/**
 * BuddyPress Group Events Template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="bpgevents-group-events">

    <h2><?php _e( 'Group Events', 'bpgevents' ); ?></h2>

    <?php if ( empty( $events ) ) : ?>

        <p><?php _e( 'No events found for this group.', 'bpgevents' ); ?></p>

    <?php else : ?>

        <ul class="bpgevents-group-events-list">

            <?php foreach ( $events as $event ) : ?>

                <?php
                $event_id   = $event->ID;
                $is_virtual = get_post_meta( $event_id, 'bpge_is_virtual', true );
                $city       = get_post_meta( $event_id, 'bpge_city', true );
                ?>

                <li class="bpgevents-group-event-item">

                    <a href="<?php echo esc_url( get_permalink( $event_id ) ); ?>">
                        <?php echo esc_html( get_the_title( $event_id ) ); ?>
                    </a>

                    <div class="bpgevents-group-event-meta">

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

            <?php endforeach; ?>

        </ul>

    <?php endif; ?>

</div>
