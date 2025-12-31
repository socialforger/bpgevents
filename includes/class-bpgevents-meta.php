<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Meta {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta' ) );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'bpgevents_event_details',
            __( 'Event Details', 'bpgevents' ),
            array( $this, 'render_meta_box' ),
            'bpge_event',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {

        $is_virtual = get_post_meta( $post->ID, 'bpge_is_virtual', true );
        $address    = get_post_meta( $post->ID, 'bpge_address', true );
        $city       = get_post_meta( $post->ID, 'bpge_city', true );
        $province   = get_post_meta( $post->ID, 'bpge_province', true );
        $country    = get_post_meta( $post->ID, 'bpge_country', true );
        $lat        = get_post_meta( $post->ID, 'bpge_lat', true );
        $lng        = get_post_meta( $post->ID, 'bpge_lng', true );
        $virtual_url = get_post_meta( $post->ID, 'bpge_virtual_url', true );

        include BPGEV_PATH . 'templates/admin/meta-box-event.php';
    }

    public function save_meta( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        update_post_meta( $post_id, 'bpge_is_virtual', isset( $_POST['bpge_is_virtual'] ) ? 1 : 0 );
        update_post_meta( $post_id, 'bpge_address', sanitize_text_field( $_POST['bpge_address'] ?? '' ) );
        update_post_meta( $post_id, 'bpge_city', sanitize_text_field( $_POST['bpge_city'] ?? '' ) );
        update_post_meta( $post_id, 'bpge_province', sanitize_text_field( $_POST['bpge_province'] ?? '' ) );
        update_post_meta( $post_id, 'bpge_country', sanitize_text_field( $_POST['bpge_country'] ?? '' ) );
        update_post_meta( $post_id, 'bpge_lat', sanitize_text_field( $_POST['bpge_lat'] ?? '' ) );
        update_post_meta( $post_id, 'bpge_lng', sanitize_text_field( $_POST['bpge_lng'] ?? '' ) );
        update_post_meta( $post_id, 'bpge_virtual_url', esc_url_raw( $_POST['bpge_virtual_url'] ?? '' ) );
    }
}
