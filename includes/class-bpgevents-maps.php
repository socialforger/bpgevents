<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Maps {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_bpgevents_get_events_map', array( $this, 'ajax_get_events_map' ) );
        add_action( 'wp_ajax_nopriv_bpgevents_get_events_map', array( $this, 'ajax_get_events_map' ) );
    }

    /**
     * Enqueue Leaflet and map scripts
     */
    public function enqueue_scripts() {

        // Leaflet core
        wp_enqueue_style(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );

        wp_enqueue_script(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            array(),
            '1.9.4',
            true
        );

        // MarkerCluster
        wp_enqueue_style(
            'leaflet-markercluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css',
            array(),
            '1.5.3'
        );

        wp_enqueue_style(
            'leaflet-markercluster-default',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css',
            array(),
            '1.5.3'
        );

        wp_enqueue_script(
            'leaflet-markercluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js',
            array( 'leaflet' ),
            '1.5.3',
            true
        );

        // Plugin map scripts
        wp_enqueue_script(
            'bpgevents-map',
            BPGEV_URL . 'assets/js/bpgevents-map.js',
            array( 'leaflet' ),
            BPGEV_VERSION,
            true
        );

        wp_enqueue_script(
            'bpgevents-map-all',
            BPGEV_URL . 'assets/js/bpgevents-map-all.js',
            array( 'leaflet', 'leaflet-markercluster' ),
            BPGEV_VERSION,
            true
        );

        wp_localize_script(
            'bpgevents-map-all',
            'BPGEV_MAP',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'marker_default' => BPGEV_URL . 'assets/img/bpgevents-default-marker.png',
                'marker_virtual' => BPGEV_URL . 'assets/img/bpgevents-virtual-placeholder.png',
            )
        );
    }

    /**
     * AJAX: return all events for the global map
     */
    public function ajax_get_events_map() {

        $events = get_posts(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        ));

        $data = array();

        foreach ( $events as $event ) {

            $is_virtual = get_post_meta( $event->ID, 'bpge_is_virtual', true );
            $lat        = get_post_meta( $event->ID, 'bpge_lat', true );
            $lng        = get_post_meta( $event->ID, 'bpge_lng', true );
            $city       = get_post_meta( $event->ID, 'bpge_city', true );

            // Skip presential events without coordinates
            if ( ! $is_virtual && ( empty( $lat ) || empty( $lng ) ) ) {
                continue;
            }

            // Virtual events: fallback to city coordinates
            if ( $is_virtual && ( empty( $lat ) || empty( $lng ) ) ) {
                $coords = BPGEVENTS_Utils::get_city_coordinates( $city );
                if ( $coords ) {
                    $lat = $coords['lat'];
                    $lng = $coords['lng'];
                }
            }

            $marker = BPGEVENTS_Markers::get_marker_for_event( $event->ID );

            $data[] = array(
                'id'       => $event->ID,
                'title'    => get_the_title( $event->ID ),
                'excerpt'  => wp_trim_words( $event->post_content, 20 ),
                'link'     => get_permalink( $event->ID ),
                'lat'      => $lat,
                'lng'      => $lng,
                'virtual'  => $is_virtual ? 1 : 0,
                'marker'   => $marker,
            );
        }

        wp_send_json( $data );
    }
}
