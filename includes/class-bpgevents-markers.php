<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Markers {

    /**
     * Return the correct marker icon for an event
     */
    public static function get_marker_for_event( $event_id ) {

        $is_virtual = get_post_meta( $event_id, 'bpge_is_virtual', true );

        // Virtual events use the placeholder icon
        if ( $is_virtual ) {
            return BPGEV_URL . 'assets/img/bpgevents-virtual-placeholder.png';
        }

        // Presential events use the default marker
        return BPGEV_URL . 'assets/img/bpgevents-default-marker.png';
    }

    /**
     * Return marker HTML for templates (optional helper)
     */
    public static function get_marker_img_html( $event_id, $size = 32 ) {

        $src = self::get_marker_for_event( $event_id );

        return sprintf(
            '<img src="%s" alt="%s" style="width:%dpx; height:%dpx;">',
            esc_url( $src ),
            esc_attr__( 'Event marker', 'bpgevents' ),
            intval( $size ),
            intval( $size )
        );
    }
}
