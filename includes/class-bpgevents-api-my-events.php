<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_API_My_Events {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {

        register_rest_route( 'bpgevents/v1', '/my-events', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_my_events' ),
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ));
    }

    public function get_my_events() {

        $user_id = get_current_user_id();

        $events = get_user_meta( $user_id, '_bpge_joined_events', true );
        if ( ! is_array( $events ) ) $events = array();

        return $events;
    }
}
