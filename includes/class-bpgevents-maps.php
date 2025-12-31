<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Maps {

    public function __construct() {
        // Placeholder per future estensioni (geocoding, clustering, ecc.)
    }

    public static function get_coordinates( $post_id ) {
        return array(
            'lat' => get_post_meta( $post_id, '_bpge_lat', true ),
            'lng' => get_post_meta( $post_id, '_bpge_lng', true ),
        );
    }
}
