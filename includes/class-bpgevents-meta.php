<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Meta {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }

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

    public function render( $post ) {

        $is_virtual = get_post_meta( $post->ID, '_bpge_virtual', true );
        $city       = get_post_meta( $post->ID, '_bpge_city', true );
        $address    = get_post_meta( $post->ID, '_bpge_address', true );
        $province   = get_post_meta( $post->ID, '_bpge_province', true );
        $country    = get_post_meta( $post->ID, '_bpge_country', true );
        $url        = get_post_meta( $post->ID, '_bpge_virtual_url', true );
        $lat        = get_post_meta( $post->ID, '_bpge_lat', true );
        $lng        = get_post_meta( $post->ID, '_bpge_lng', true );

        include plugin_dir_path( __FILE__ ) . '../templates/admin-meta-box.php';
    }

    public function save( $post_id ) {

        if ( ! isset( $_POST['bpge_event_nonce'] ) ) return;

        update_post_meta( $post_id, '_bpge_virtual',      isset($_POST['bpge_virtual']) ? 1 : 0 );
        update_post_meta( $post_id, '_bpge_city',         sanitize_text_field($_POST['bpge_city'] ?? '') );
        update_post_meta( $post_id, '_bpge_address',      sanitize_text_field($_POST['bpge_address'] ?? '') );
        update_post_meta( $post_id, '_bpge_province',     sanitize_text_field($_POST['bpge_province'] ?? '') );
        update_post_meta( $post_id, '_bpge_country',      sanitize_text_field($_POST['bpge_country'] ?? '') );
        update_post_meta( $post_id, '_bpge_virtual_url',  esc_url_raw($_POST['bpge_virtual_url'] ?? '') );
        update_post_meta( $post_id, '_bpge_lat',          sanitize_text_field($_POST['bpge_lat'] ?? '') );
        update_post_meta( $post_id, '_bpge_lng',          sanitize_text_field($_POST['bpge_lng'] ?? '') );
    }
}
