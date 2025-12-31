<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Utility functions for BPGE Events
 */
class BPGEVENTS_Utils {

    /**
     * Check if event is virtual
     */
    public static function is_virtual( $post_id ) {
        return (bool) get_post_meta( $post_id, '_bpge_virtual', true );
    }

    /**
     * Get event coordinates
     */
    public static function get_coordinates( $post_id ) {
        return array(
            'lat' => get_post_meta( $post_id, '_bpge_lat', true ),
            'lng' => get_post_meta( $post_id, '_bpge_lng', true ),
        );
    }

    /**
     * Get event location string
     */
    public static function get_location_string( $post_id ) {

        if ( self::is_virtual( $post_id ) ) {
            return __( 'Virtual Event', 'bpgevents' );
        }

        $city     = get_post_meta( $post_id, '_bpge_city', true );
        $province = get_post_meta( $post_id, '_bpge_province', true );
        $country  = get_post_meta( $post_id, '_bpge_country', true );

        $parts = array_filter( array( $city, $province, $country ) );

        if ( empty( $parts ) ) {
            return __( 'Location not specified', 'bpgevents' );
        }

        return implode( ', ', $parts );
    }

    /**
     * Get virtual event URL
     */
    public static function get_virtual_url( $post_id ) {
        if ( ! self::is_virtual( $post_id ) ) {
            return '';
        }
        return esc_url( get_post_meta( $post_id, '_bpge_virtual_url', true ) );
    }
}
