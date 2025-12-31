<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_API {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {

        register_rest_route( 'bpgevents/v1', '/events', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_events' ),
        ));
    }

    public function get_events() {

        $query = new WP_Query(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
        ));

        if ( ! $query->have_posts() ) {
            return array( 'message' => __( 'No events available.', 'bpgevents' ) );
        }

        $events = array();

        foreach ( $query->posts as $post ) {
            $events[] = array(
                'id'    => $post->ID,
                'title' => $post->post_title,
                'link'  => get_permalink( $post->ID ),
            );
        }

        return $events;
    }
}
