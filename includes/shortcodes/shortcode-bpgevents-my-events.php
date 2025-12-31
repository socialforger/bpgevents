<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode: [bpgevents_my_events]
 * Displays the list of events created by the logged‑in user
 */
class BPGEVENTS_Shortcode_My_Events {

    public function __construct() {
        add_shortcode( 'bpgevents_my_events', array( $this, 'render' ) );
    }

    /**
     * Render the user's events list
     */
    public function render( $atts ) {

        if ( ! is_user_logged_in() ) {
            return '<p>' . __( 'You must be logged in to view your events.', 'bpgevents' ) . '</p>';
        }

        $user_id = get_current_user_id();

        $events = get_posts( array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
            'post_status'    => array( 'publish', 'draft', 'pending' ),
            'author'         => $user_id,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        ob_start();

        echo '<div class="bpgevents-my-events">';

        echo '<h3>' . __( 'My Events', 'bpgevents' ) . '</h3>';

        if ( empty( $events ) ) {
            echo '<p>' . __( 'You have not created any events yet.', 'bpgevents' ) . '</p>';
            echo '</div>';
            return ob_get_clean();
        }

        echo '<ul class="bpgevents-my-events-ul">';

        foreach ( $events as $event ) {

            $is_virtual = get_post_meta( $event->ID, 'bpge_is_virtual', true );
            $city       = get_post_meta( $event->ID, 'bpge_city', true );

            echo '<li class="bpgevents-my-event-item">';

            // Title
            echo '<a class="bpgevents-event-title" href="' . esc_url( get_permalink( $event->ID ) ) . '">';
            echo esc_html( get_the_title( $event->ID ) );
            echo '</a>';

            // Meta
            echo '<div class="bpgevents-event-meta">';

            echo $is_virtual
                ? '<span class="bpgevents-tag bpgevents-tag-virtual">' . __( 'Virtual', 'bpgevents' ) . '</span>'
                : '<span class="bpgevents-tag bpgevents-tag-presential">' . __( 'Presential', 'bpgevents' ) . '</span>';

            if ( $city ) {
                echo '<span class="bpgevents-city">' . esc_html( $city ) . '</span>';
            }

            echo '</div>';

            // Edit link
            echo '<div class="bpgevents-my-event-actions">';
            echo '<a class="bpgevents-edit-link" href="' . esc_url( get_edit_post_link( $event->ID ) ) . '">';
            echo __( 'Edit Event', 'bpgevents' );
            echo '</a>';
            echo '</div>';

            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';

        return ob_get_clean();
    }
}

new BPGEVENTS_Shortcode_My_Events();
