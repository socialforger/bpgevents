<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Map utilities for BPGE Events
 * Handles coordinates, validation, normalization and geocoding.
 */
class BPGEVENTS_Maps {

    /**
     * Default fallback coordinates (Rome)
     */
    const DEFAULT_LAT = 41.9028;
    const DEFAULT_LNG = 12.4964;

    public function __construct() {
        // Future hooks if needed
    }

    /**
     * Returns coordinates or fallback if missing
     */
    public static function get_coordinates_or_default( $post_id ) {

        $coords = BPGEVENTS_Utils::get_coordinates( $post_id );

        if ( self::is_valid_coordinates( $coords ) ) {
            return $coords;
        }

        return array(
            'lat' => self::DEFAULT_LAT,
            'lng' => self::DEFAULT_LNG,
        );
    }

    /**
     * Validate coordinates
     */
    public static function is_valid_coordinates( $coords ) {

        if ( empty( $coords['lat'] ) || empty( $coords['lng'] ) ) {
            return false;
        }

        if ( ! is_numeric( $coords['lat'] ) || ! is_numeric( $coords['lng'] ) ) {
            return false;
        }

        if ( $coords['lat'] < -90 || $coords['lat'] > 90 ) {
            return false;
        }

        if ( $coords['lng'] < -180 || $coords['lng'] > 180 ) {
            return false;
        }

        return true;
    }

    /**
     * Normalize address string for geocoding or display
     */
    public static function normalize_address( $post_id ) {

        if ( BPGEVENTS_Utils::is_virtual( $post_id ) ) {
            return __( 'Virtual Event', 'bpgevents' );
        }

        $city     = get_post_meta( $post_id, '_bpge_city', true );
        $address  = get_post_meta( $post_id, '_bpge_address', true );
        $province = get_post_meta( $post_id, '_bpge_province', true );
        $country  = get_post_meta( $post_id, '_bpge_country', true );

        $parts = array_filter( array( $address, $city, $province, $country ) );

        if ( empty( $parts ) ) {
            return '';
        }

        return implode( ', ', $parts );
    }

    /**
     * Geocode address using Nominatim (OpenStreetMap)
     */
    public static function geocode_address( $address ) {

        if ( empty( $address ) ) {
            return false;
        }

        $url = add_query_arg(array(
            'q'      => urlencode( $address ),
            'format' => 'json',
            'limit'  => 1,
        ), 'https://nominatim.openstreetmap.org/search');

        $response = wp_remote_get( $url, array(
            'timeout' => 10,
            'headers' => array(
                'User-Agent' => 'BPGE Events Plugin (WordPress)'
            )
        ));

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( empty( $body[0] ) ) {
            return false;
        }

        return array(
            'lat' => floatval( $body[0]['lat'] ),
            'lng' => floatval( $body[0]['lon'] ),
        );
    }
}
