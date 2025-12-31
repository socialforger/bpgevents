<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers the bpge_event custom post type
 * and handles archive filtering (search, city, type).
 */
class BPGEVENTS_CPT {

    public function __construct() {
        add_action( 'init', array( $this, 'register_cpt' ) );
        add_action( 'pre_get_posts', array( $this, 'filter_archive_query' ) );
    }

    /**
     * Register Custom Post Type: bpge_event
     */
    public function register_cpt() {

        $labels = array(
            'name'               => __( 'Events', 'bpgevents' ),
            'singular_name'      => __( 'Event', 'bpgevents' ),
            'add_new'            => __( 'Add New Event', 'bpgevents' ),
            'add_new_item'       => __( 'Create New Event', 'bpgevents' ),
            'edit_item'          => __( 'Edit Event', 'bpgevents' ),
            'new_item'           => __( 'New Event', 'bpgevents' ),
            'view_item'          => __( 'View Event', 'bpgevents' ),
            'search_items'       => __( 'Search Events', 'bpgevents' ),
            'not_found'          => __( 'No events found.', 'bpgevents' ),
            'not_found_in_trash' => __( 'No events found in trash.', 'bpgevents' ),
            'all_items'          => __( 'All Events', 'bpgevents' ),
            'menu_name'          => __( 'Events', 'bpgevents' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'rewrite'            => array( 'slug' => 'events' ),
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author' ),
            'show_in_rest'       => true,
            'rest_base'          => 'bpgevents',
            'capability_type'    => 'post',
        );

        register_post_type( 'bpge_event', $args );
    }

    /**
     * Filter archive query for search, city, and event type
     */
    public function filter_archive_query( $query ) {

        // Only modify front-end main query for bpge_event archive
        if ( is_admin() ) return;
        if ( ! $query->is_main_query() ) return;
        if ( ! $query->is_post_type_archive( 'bpge_event' ) ) return;

        /**
         * ---------------------------------------------------------
         *  TEXT SEARCH (WordPress native: ?s=keyword)
         * ---------------------------------------------------------
         */

        // Nothing to do: WP handles "s" automatically


        /**
         * ---------------------------------------------------------
         *  FILTER BY CITY (?bpge_city=Rome)
         * ---------------------------------------------------------
         */
        if ( ! empty( $_GET['bpge_city'] ) ) {
            $query->set( 'meta_query', array(
                array(
                    'key'     => '_bpge_city',
                    'value'   => sanitize_text_field( $_GET['bpge_city'] ),
                    'compare' => 'LIKE',
                )
            ));
        }

        /**
         * ---------------------------------------------------------
         *  FILTER BY TYPE (?bpge_type=virtual|presential)
         * ---------------------------------------------------------
         */
        if ( ! empty( $_GET['bpge_type'] ) ) {

            $type = sanitize_text_field( $_GET['bpge_type'] );

            if ( $type === 'virtual' ) {
                $query->set( 'meta_query', array(
                    array(
                        'key'     => '_bpge_virtual',
                        'value'   => '1',
                        'compare' => '=',
                    )
                ));
            }

            if ( $type === 'presential' ) {
                $query->set( 'meta_query', array(
                    array(
                        'key'     => '_bpge_virtual',
                        'value'   => '0',
                        'compare' => '=',
                    )
                ));
            }
        }

        /**
         * ---------------------------------------------------------
         *  ORDERING (default: newest first)
         * ---------------------------------------------------------
         */
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
