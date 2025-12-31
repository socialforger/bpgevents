<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Map utilities for BPGE Events
 * Handles coordinates, validation, normalization and map data generation.
 */
class BPGEVENTS_Maps {

    /**
     * Default fallback coordinates (Rome)
     */
    const DEFAULT_LAT = 41.9028;
    const DEFAULT_LNG = 12.4964;

    public function __construct() {
        // Placeholder for future geocoding or map providers
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
            return __( 'Location not specified', 'bpgevents' );
        }

        return implode( ', ', $parts );
    }

    /**
     * Build marker data for JS maps
     */
    public static function get_marker_data( $post_id ) {

        $coords = self::get_coordinates_or_default( $post_id );

        return array(
            'id'       => $post_id,
            'title'    => get_the_title( $post_id ),
            'url'      => get_permalink( $post_id ),
            'lat'      => floatval( $coords['lat'] ),
            'lng'      => floatval( $coords['lng'] ),
            'virtual'  => BPGEVENTS_Utils::is_virtual( $post_id ),
            'location' => self::normalize_address( $post_id ),
        );
    }

    /**
     * Build marker list for all events
     */
    public static function get_all_markers() {

        $query = new WP_Query(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
        ));

        $markers = array();

        foreach ( $query->posts as $post ) {
            $markers[] = self::get_marker_data( $post->ID );
        }

        return $markers;
    }

    /**
     * Compute bounding box for map auto-fit
     */
    public static function get_bounds( $markers ) {

        if ( empty( $markers ) ) {
            return array(
                'min_lat' => self::DEFAULT_LAT,
                'max_lat' => self::DEFAULT_LAT,
                'min_lng' => self::DEFAULT_LNG,
                'max_lng' => self::DEFAULT_LNG,
            );
        }

        $lats = array_column( $markers, 'lat' );
        $lngs = array_column( $markers, 'lng' );

        return array(
            'min_lat' => min( $lats ),
            'max_lat' => max( $lats ),
            'min_lng' => min( $lngs ),
            'max_lng' => max( $lngs ),
        );
    }

    /**
     * Placeholder for future geocoding (OpenStreetMap, Nominatim, etc.)
     */
    public static function geocode_address( $address ) {
        // Future implementation
        return false;
    }
}
