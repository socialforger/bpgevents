<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handles event meta fields (location, virtual, coordinates, geocoding)
 */
class BPGEVENTS_Meta {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }

    /**
     * Register meta box
     */
    public function add_box() {
        add_meta_box(
            'bpgevents_details',
            __( 'Event Details', 'bpgevents' ),
            array( $this, 'render' ),
            'bpge_event',
            'normal',
            'high'
        );
    }

    /**
     * Render meta box template
     */
    public function render( $post ) {

        // Retrieve stored values
        $is_virtual = get_post_meta( $post->ID, '_bpge_virtual', true );
        $city       = get_post_meta( $post->ID, '_bpge_city', true );
        $address    = get_post_meta( $post->ID, '_bpge_address', true );
        $province   = get_post_meta( $post->ID, '_bpge_province', true );
        $country    = get_post_meta( $post->ID, '_bpge_country', true );
        $url        = get_post_meta( $post->ID, '_bpge_virtual_url', true );
        $lat        = get_post_meta( $post->ID, '_bpge_lat', true );
        $lng        = get_post_meta( $post->ID, '_bpge_lng', true );

        // Include admin template
        include plugin_dir_path( __FILE__ ) . '../templates/bpgevents-admin-meta-box.php';
    }

    /**
     * Save meta fields
     */
    public function save( $post_id ) {

        // Security: nonce
        if ( ! isset( $_POST['bpge_event_nonce'] ) ) return;
        if ( ! wp_verify_nonce( $_POST['bpge_event_nonce'], 'bpge_event_save' ) ) return;

        // Prevent autosave overwrite
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // Permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        // Save fields
        update_post_meta( $post_id, '_bpge_virtual',      isset($_POST['bpge_virtual']) ? 1 : 0 );
        update_post_meta( $post_id, '_bpge_city',         sanitize_text_field($_POST['bpge_city'] ?? '') );
        update_post_meta( $post_id, '_bpge_address',      sanitize_text_field($_POST['bpge_address'] ?? '') );
        update_post_meta( $post_id, '_bpge_province',     sanitize_text_field($_POST['bpge_province'] ?? '') );
        update_post_meta( $post_id, '_bpge_country',      sanitize_text_field($_POST['bpge_country'] ?? '') );
        update_post_meta( $post_id, '_bpge_virtual_url',  esc_url_raw($_POST['bpge_virtual_url'] ?? '') );
        update_post_meta( $post_id, '_bpge_lat',          sanitize_text_field($_POST['bpge_lat'] ?? '') );
        update_post_meta( $post_id, '_bpge_lng',          sanitize_text_field($_POST['bpge_lng'] ?? '') );

        /**
         * ---------------------------------------------------------
         *  GEOCODING AUTOMATICO (solo se attivato e necessario)
         * ---------------------------------------------------------
         */

        $options = get_option( 'bpgevents_settings' );
        $enable_geocoding = ! empty( $options['enable_geocoding'] );

        // Skip geocoding for virtual events
        if ( isset($_POST['bpge_virtual']) ) {
            return;
        }

        $lat = get_post_meta( $post_id, '_bpge_lat', true );
        $lng = get_post_meta( $post_id, '_bpge_lng', true );

        // Only geocode if coordinates are missing
        if ( $enable_geocoding && ( empty( $lat ) || empty( $lng ) ) ) {

            $address = BPGEVENTS_Maps::normalize_address( $post_id );

            if ( ! empty( $address ) ) {

                $coords = BPGEVENTS_Maps::geocode_address( $address );

                if ( $coords ) {
                    update_post_meta( $post_id, '_bpge_lat', $coords['lat'] );
                    update_post_meta( $post_id, '_bpge_lng', $coords['lng'] );
                }
            }
        }
    }
}
