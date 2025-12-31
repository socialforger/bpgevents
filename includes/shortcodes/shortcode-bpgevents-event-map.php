<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode: [bpgevents_event_map id="123"]
 * Displays a Leaflet map for a single event
 */
class BPGEVENTS_Shortcode_Event_Map {

    public function __construct() {
        add_shortcode( 'bpgevents_event_map', array( $this, 'render' ) );
    }

    /**
     * Render the event map
     */
    public function render( $atts ) {

        $atts = shortcode_atts( array(
            'id' => 0,
            'height' => '350px',
            'zoom'   => 14,
        ), $atts );

        $event_id = intval( $atts['id'] );

        if ( ! $event_id || get_post_type( $event_id ) !== 'bpge_event' ) {
            return '<p>' . __( 'Invalid event ID.', 'bpgevents' ) . '</p>';
        }

        $is_virtual = get_post_meta( $event_id, 'bpge_is_virtual', true );
        $lat        = get_post_meta( $event_id, 'bpge_lat', true );
        $lng        = get_post_meta( $event_id, 'bpge_lng', true );
        $city       = get_post_meta( $event_id, 'bpge_city', true );

        // Virtual event fallback: use city coordinates
        if ( $is_virtual && ( empty( $lat ) || empty( $lng ) ) ) {
            $coords = BPGEVENTS_Utils::get_city_coordinates( $city );
            if ( $coords ) {
                $lat = $coords['lat'];
                $lng = $coords['lng'];
            }
        }

        // If still no coordinates, stop
        if ( empty( $lat ) || empty( $lng ) ) {
            return '<p>' . __( 'This event does not have valid map coordinates.', 'bpgevents' ) . '</p>';
        }

        // Unique map ID
        $map_id = 'bpgevents-map-' . $event_id . '-' . wp_generate_password( 6, false, false );

        ob_start();

        echo '<div class="bpgevents-event-map" id="' . esc_attr( $map_id ) . '" 
                  style="width:100%; height:' . esc_attr( $atts['height'] ) . ';"></div>';

        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {

            var map = L.map('<?php echo esc_js( $map_id ); ?>').setView(
                [<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>],
                <?php echo intval( $atts['zoom'] ); ?>
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var markerIcon = L.icon({
                iconUrl: '<?php echo esc_url( BPGEVENTS_Markers::get_marker_for_event( $event_id ) ); ?>',
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            L.marker(
                [<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>],
                { icon: markerIcon }
            ).addTo(map)
             .bindPopup('<?php echo esc_js( get_the_title( $event_id ) ); ?>');
        });
        </script>
        <?php

        return ob_get_clean();
    }
}

new BPGEVENTS_Shortcode_Event_Map();
