<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Participation {

    public function __construct() {
        add_action( 'wp_ajax_bpgevents_toggle_participation', array( $this, 'toggle' ) );
        add_action( 'wp_ajax_nopriv_bpgevents_toggle_participation', array( $this, 'deny' ) );
    }

    public function deny() {
        wp_send_json_error( array( 'message' => __( 'You must be logged in to join this event.', 'bpgevents' ) ) );
    }

    public function toggle() {

        $event_id = intval( $_POST['event_id'] );
        $user_id  = get_current_user_id();

        if ( ! $event_id ) wp_send_json_error();

        $joined = get_user_meta( $user_id, '_bpge_joined_events', true );
        if ( ! is_array( $joined ) ) $joined = array();

        if ( in_array( $event_id, $joined ) ) {
            $joined = array_diff( $joined, array( $event_id ) );
            $status = false;
        } else {
            $joined[] = $event_id;
            $status = true;
        }

        update_user_meta( $user_id, '_bpge_joined_events', $joined );

        wp_send_json_success(array(
            'joined'       => $status,
            'participants' => count( $joined ),
        ));
    }
}
