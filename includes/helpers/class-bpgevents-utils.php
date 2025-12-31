<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Utils {

    /**
     * Sanitize text input
     */
    public static function sanitize( $value ) {
        return sanitize_text_field( $value );
    }

    /**
     * Get coordinates for a city using OpenStreetMap Nominatim
     * Used as fallback for virtual events without lat/lng
     */
    public static function get_city_coordinates( $city ) {

        if ( empty( $city ) ) {
            return false;
        }

        $url = add_query_arg(
            array(
                'q'      => urlencode( $city ),
                'format' => 'json',
                'limit'  => 1,
            ),
            'https://nominatim.openstreetmap.org/search'
        );

        $response = wp_remote_get( $url, array(
            'timeout' => 10,
            'headers' => array(
                'User-Agent' => 'BPGE Events Plugin'
            )
        ) );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( empty( $data ) || ! isset( $data[0] ) ) {
            return false;
        }

        return array(
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon'],
        );
    }

    /**
     * Format a date for display
     */
    public static function format_date( $timestamp ) {

        if ( ! $timestamp ) {
            return '';
        }

        return date_i18n( get_option( 'date_format' ), $timestamp );
    }

    /**
     * Format a datetime for display
     */
    public static function format_datetime( $timestamp ) {

        if ( ! $timestamp ) {
            return '';
        }

        return date_i18n(
            get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
            $timestamp
        );
    }

    /**
     * Convert a string to a timestamp
     */
    public static function to_timestamp( $date_string ) {

        if ( empty( $date_string ) ) {
            return false;
        }

        return strtotime( $date_string );
    }

    /**
     * Return a safe array value
     */
    public static function array_get( $array, $key, $default = '' ) {

        if ( isset( $array[ $key ] ) ) {
            return $array[ $key ];
        }

        return $default;
    }

    /**
     * Generate a random string
     */
    public static function random_string( $length = 12 ) {
        return wp_generate_password( $length, false, false );
    }

    /**
     * Check if a string is a valid URL
     */
    public static function is_url( $value ) {
        return filter_var( $value, FILTER_VALIDATE_URL ) !== false;
    }
}
