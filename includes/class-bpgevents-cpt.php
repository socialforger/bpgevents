<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_CPT {

    public function __construct() {
        add_action( 'init', array( $this, 'register_cpt' ) );
    }

    public function register_cpt() {

        $labels = array(
            'name'               => __( 'Events', 'bpgevents' ),
            'singular_name'      => __( 'Event', 'bpgevents' ),
            'add_new'            => __( 'Add New Event', 'bpgevents' ),
            'add_new_item'       => __( 'Add New Event', 'bpgevents' ),
            'edit_item'          => __( 'Edit Event', 'bpgevents' ),
            'new_item'           => __( 'New Event', 'bpgevents' ),
            'view_item'          => __( 'View Event', 'bpgevents' ),
            'search_items'       => __( 'Search Events', 'bpgevents' ),
            'not_found'          => __( 'No events found.', 'bpgevents' ),
            'not_found_in_trash' => __( 'No events found in Trash.', 'bpgevents' ),
        );

        $args = array(
            'labels'        => $labels,
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array( 'slug' => 'events' ),
            'supports'      => array( 'title', 'editor', 'thumbnail' ),
            'show_in_rest'  => true,
        );

        register_post_type( 'bpge_event', $args );
    }
}
