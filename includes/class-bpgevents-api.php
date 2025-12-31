<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_API {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {

        register_rest_route(
            'bpgevents/v1',
            '/events',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_events' ),
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route(
            'bpgevents/v1',
            '/event/(?P<id>\d+)',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_event' ),
                'permission_callback' => '__return_true',
            )
        );
    }

    /**
     * Return all events
     */
    public function get_events( $request ) {

        $events = get_posts(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        ));

        $data = array();

        foreach ( $events as $event ) {
            $data[] = $this->format_event_data( $event );
        }

        return rest_ensure_response( $data );
    }

    /**
     * Return a single event
     */
    public function get_event( $request ) {

        $id = intval( $request['id'] );
        $event = get_post( $id );

        if ( ! $event || $event->post_type !== 'bpge_event' ) {
            return new WP_Error(
                'event_not_found',
                __( 'Event not found', 'bpgevents' ),
                array( 'status' => 404 )
            );
        }

        return rest_ensure_response( $this->format_event_data( $event ) );
    }

    /**
     * Format event data for API output
     */
    private function format_event_data( $event ) {

        $is_virtual  = get_post_meta( $event->ID, 'bpge_is_virtual', true );
        $address     = get_post_meta( $event->ID, 'bpge_address', true );
        $city        = get_post_meta( $event->ID, 'bpge_city', true );
        $province    = get_post_meta( $event->ID, 'bpge_province', true );
        $country     = get_post_meta( $event->ID, 'bpge_country', true );
        $lat         = get_post_meta( $event->ID, 'bpge_lat', true );
        $lng         = get_post_meta( $event->ID, 'bpge_lng', true );
        $virtual_url = get_post_meta( $event->ID, 'bpge_virtual_url', true );

        return array(
            'id'          => $event->ID,
            'title'       => get_the_title( $event->ID ),
            'content'     => apply_filters( 'the_content', $event->post_content ),
            'excerpt'     => wp_trim_words( $event->post_content, 30 ),
            'link'        => get_permalink( $event->ID ),
            'author'      => get_the_author_meta( 'display_name', $event->post_author ),
            'is_virtual'  => $is_virtual ? 1 : 0,
            'address'     => $address,
            'city'        => $city,
            'province'    => $province,
            'country'     => $country,
            'lat'         => $lat,
            'lng'         => $lng,
            'virtual_url' => $virtual_url,
            'categories'  => wp_get_post_terms( $event->ID, 'bpge_event_category', array( 'fields' => 'names' ) ),
            'thumbnail'   => get_the_post_thumbnail_url( $event->ID, 'large' ),
            'date'        => get_the_date( '', $event->ID ),
        );
    }
}
