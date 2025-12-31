<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode: [bpgevents_events_list]
 * Displays a list of all published events
 */
class BPGEVENTS_Shortcode_Events_List {

    public function __construct() {
        add_shortcode( 'bpgevents_events_list', array( $this, 'render' ) );
    }

    /**
     * Render the events list
     */
    public function render( $atts ) {

        $atts = shortcode_atts( array(
            'limit' => -1,
            'order' => 'DESC',
        ), $atts );

        $events = get_posts( array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => $atts['order'],
        ) );

        ob_start();

        echo '<div class="bpgevents-events-list">';

        if ( empty( $events ) ) {
            echo '<p>' . __( 'No events found.', 'bpgevents' ) . '</p>';
            echo '</div>';
            return ob_get_clean();
        }

        echo '<ul class="bpgevents-events-ul">';

        foreach ( $events as $event ) {

            $is_virtual = get_post_meta( $event->ID, 'bpge_is_virtual', true );
            $city       = get_post_meta( $event->ID, 'bpge_city', true );

            echo '<li class="bpgevents-event-item">';

            echo '<a class="bpgevents-event-title" href="' . esc_url( get_permalink( $event->ID ) ) . '">';
            echo esc_html( get_the_title( $event->ID ) );
            echo '</a>';

            echo '<div class="bpgevents-event-meta">';

            echo $is_virtual
                ? '<span class="bpgevents-tag bpgevents-tag-virtual">' . __( 'Virtual', 'bpgevents' ) . '</span>'
                : '<span class="bpgevents-tag bpgevents-tag-presential">' . __( 'Presential', 'bpgevents' ) . '</span>';

            if ( $city ) {
                echo '<span class="bpgevents-city">' . esc_html( $city ) . '</span>';
            }

            echo '</div>';

            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';

        return ob_get_clean();
    }
}

new BPGEVENTS_Shortcode_Events_List();
