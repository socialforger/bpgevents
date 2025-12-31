<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * BuddyPress integration for BPGE Events
 */
class BPGEVENTS_BP {

    public function __construct() {

        // Add "Events" tab inside BuddyPress Groups
        add_action( 'bp_groups_setup_nav', array( $this, 'add_group_events_tab' ) );

        // Handle group events screen
        add_action( 'bp_screens', array( $this, 'load_group_events_screen' ) );
    }

    /**
     * Add "Events" tab to BuddyPress group navigation
     */
    public function add_group_events_tab() {

        if ( ! bp_is_active( 'groups' ) ) {
            return;
        }

        bp_core_new_subnav_item( array(
            'name'            => __( 'Events', 'bpgevents' ),
            'slug'            => 'events',
            'parent_slug'     => bp_get_current_group_slug(),
            'parent_url'      => bp_get_group_permalink( groups_get_current_group() ),
            'screen_function' => array( $this, 'load_group_events_screen' ),
            'position'        => 40,
            'user_has_access' => true,
        ) );
    }

    /**
     * Load the group events screen
     */
    public function load_group_events_screen() {

        if ( ! bp_is_groups_component() || ! bp_is_current_action( 'events' ) ) {
            return;
        }

        add_action( 'bp_template_content', array( $this, 'render_group_events_page' ) );

        bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }

    /**
     * Render the group events page
     */
    public function render_group_events_page() {

        $group_id = bp_get_current_group_id();

        if ( ! $group_id ) {
            echo '<p>' . __( 'Group not found.', 'bpgevents' ) . '</p>';
            return;
        }

        // Fetch events authored by group members
        $group_members = groups_get_group_members( array(
            'group_id' => $group_id,
            'per_page' => -1,
        ) );

        $member_ids = array();

        if ( ! empty( $group_members['members'] ) ) {
            foreach ( $group_members['members'] as $member ) {
                $member_ids[] = $member->ID;
            }
        }

        $events = get_posts( array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'author__in'     => $member_ids,
        ) );

        echo '<div class="bpgevents-group-events">';

        echo '<h2>' . __( 'Group Events', 'bpgevents' ) . '</h2>';

        if ( empty( $events ) ) {
            echo '<p>' . __( 'No events found for this group.', 'bpgevents' ) . '</p>';
            echo '</div>';
            return;
        }

        echo '<ul class="bpgevents-group-events-list">';

        foreach ( $events as $event ) {

            $is_virtual = get_post_meta( $event->ID, 'bpge_is_virtual', true );
            $city       = get_post_meta( $event->ID, 'bpge_city', true );

            echo '<li class="bpgevents-group-event-item">';

            echo '<a href="' . esc_url( get_permalink( $event->ID ) ) . '">';
            echo esc_html( get_the_title( $event->ID ) );
            echo '</a>';

            echo '<div class="bpgevents-group-event-meta">';

            echo $is_virtual
                ? '<span class="bpgevents-tag bpgevents-tag-virtual">' . __( 'Virtual', 'bpgevents' ) . '</span>'
                : '<span class="bpgevents-tag bpgevents-tag-presential">' . __( 'Presential', 'bpgevents' ) . '</span>';

            if ( $city ) {
                echo '<span class="bpgevents-city">' . esc_html( $city ) . '</span>';
            }

            echo '</div>';

            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
    }
}
