<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Shortcode_My_Events {

    public function __construct() {
        add_shortcode( 'bpgevents_my_events', array( $this, 'render' ) );
    }

    public function render() {

        if ( ! is_user_logged_in() ) {
            return '<p>' . __( 'You must be logged in to view your events.', 'bpgevents' ) . '</p>';
        }

        $user_id = get_current_user_id();
        $joined  = get_user_meta( $user_id, '_bpge_joined_events', true );

        if ( ! is_array( $joined ) || empty( $joined ) ) {
            return '<p>' . __( 'You have not created any events yet.', 'bpgevents' ) . '</p>';
        }

        ob_start();

        echo '<div class="bpge-my-events">';

        foreach ( $joined as $event_id ) {

            if ( ! get_post( $event_id ) ) continue;

            echo '<div class="bpge-event-box">';
            echo '<h3><a href="' . get_permalink( $event_id ) . '">' . get_the_title( $event_id ) . '</a></h3>';
            echo '<p>' . BPGEVENTS_Utils::get_location_string( $event_id ) . '</p>';
            echo '</div>';
        }

        echo '</div>';

        return ob_get_clean();
    }
}
