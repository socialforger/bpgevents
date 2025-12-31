<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Participation {

    public function __construct() {
        add_action( 'init', array( $this, 'register_participation_meta' ) );
        add_action( 'wp_ajax_bpgevents_toggle_participation', array( $this, 'ajax_toggle_participation' ) );
        add_action( 'wp_ajax_nopriv_bpgevents_toggle_participation', array( $this, 'ajax_not_logged_in' ) );
    }

    /**
     * Register meta key for event participation
     */
    public function register_participation_meta() {
        register_post_meta(
            'bpge_event',
            'bpge_participants',
            array(
                'type'         => 'array',
                'single'       => true,
                'show_in_rest' => true,
                'default'      => array(),
            )
        );
    }

    /**
     * Handle AJAX for participation toggle
     */
    public function ajax_toggle_participation() {

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'You must be logged in.', 'bpgevents' ) ) );
        }

        $event_id = intval( $_POST['event_id'] ?? 0 );

        if ( ! $event_id || get_post_type( $event_id ) !== 'bpge_event' ) {
            wp_send_json_error( array( 'message' => __( 'Invalid event.', 'bpgevents' ) ) );
        }

        $user_id = get_current_user_id();
        $participants = get_post_meta( $event_id, 'bpge_participants', true );

        if ( ! is_array( $participants ) ) {
            $participants = array();
        }

        if ( in_array( $user_id, $participants ) ) {
            // Remove participation
            $participants = array_diff( $participants, array( $user_id ) );
            update_post_meta( $event_id, 'bpge_participants', $participants );

            wp_send_json_success( array(
                'status'  => 'removed',
                'message' => __( 'You are no longer participating.', 'bpgevents' ),
                'count'   => count( $participants ),
            ) );
        } else {
            // Add participation
            $participants[] = $user_id;
            $participants   = array_unique( $participants );
            update_post_meta( $event_id, 'bpge_participants', $participants );

            wp_send_json_success( array(
                'status'  => 'added',
                'message' => __( 'You are now participating.', 'bpgevents' ),
                'count'   => count( $participants ),
            ) );
        }
    }

    /**
     * Handle AJAX for non-logged users
     */
    public function ajax_not_logged_in() {
        wp_send_json_error( array(
            'message' => __( 'You must be logged in to participate.', 'bpgevents' )
        ) );
    }

    /**
     * Check if a user is participating in an event
     */
    public static function is_participating( $event_id, $user_id = null ) {

        if ( ! $user_id ) {
            $user_id = get_current_user_id();
        }

        if ( ! $user_id ) {
            return false;
        }

        $participants = get_post_meta( $event_id, 'bpge_participants', true );

        if ( ! is_array( $participants ) ) {
            return false;
        }

        return in_array( $user_id, $participants );
    }

    /**
     * Get participant count
     */
    public static function get_participant_count( $event_id ) {
        $participants = get_post_meta( $event_id, 'bpge_participants', true );
        return is_array( $participants ) ? count( $participants ) : 0;
    }
}
