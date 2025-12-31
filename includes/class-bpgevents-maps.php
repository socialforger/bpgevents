<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Maps {

    public function __construct() {
        // Qui potrai agganciare geocoding o funzioni future
    }

    /**
     * Ritorna le coordinate, con fallback se mancanti
     */
    public static function get_coordinates_or_default( $post_id, $default_lat = 41.9028, $default_lng = 12.4964 ) {
        $coords = BPGEVENTS_Utils::get_coordinates( $post_id );

        if ( empty( $coords['lat'] ) || empty( $coords['lng'] ) ) {
            return array(
                'lat' => $default_lat,
                'lng' => $default_lng,
            );
        }

        return $coords;
    }
}
