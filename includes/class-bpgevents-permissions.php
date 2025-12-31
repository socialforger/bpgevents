<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Permissions {

    public function __construct() {
        add_filter( 'user_has_cap', array( $this, 'filter_capabilities' ), 10, 4 );
    }

    /**
     * Custom capability logic for event creation and editing
     */
    public function filter_capabilities( $all_caps, $caps, $args, $user ) {

        // Editing a specific event
        if ( isset( $args[0] ) && $args[0] === 'edit_post' ) {

            $post_id = intval( $args[2] );
            $post    = get_post( $post_id );

            if ( $post && $post->post_type === 'bpge_event' ) {

                // Only the event author or admins can edit
                if ( intval( $post->post_author ) === intval( $user->ID ) ) {
                    $all_caps['edit_post'] = true;
                }
            }
        }

        // Deleting a specific event
        if ( isset( $args[0] ) && $args[0] === 'delete_post' ) {

            $post_id = intval( $args[2] );
            $post    = get_post( $post_id );

            if ( $post && $post->post_type === 'bpge_event' ) {

                // Only the event author or admins can delete
                if ( intval( $post->post_author ) === intval( $user->ID ) ) {
                    $all_caps['delete_post'] = true;
                }
            }
        }

        return $all_caps;
    }

    /**
     * Check if a user can create events
     */
    public static function user_can_create_events( $user_id = null ) {

        if ( ! $user_id ) {
            $user_id = get_current_user_id();
        }

        if ( ! $user_id ) {
            return false;
        }

        // Basic rule: all logged-in users can create events
        return true;
    }

    /**
     * Check if a user can edit a specific event
     */
    public static function user_can_edit_event( $event_id, $user_id = null ) {

        if ( ! $user_id ) {
            $user_id = get_current_user_id();
        }

        if ( ! $user_id ) {
            return false;
        }

        $post = get_post( $event_id );

        if ( ! $post || $post->post_type !== 'bpge_event' ) {
            return false;
        }

        // Author or admin
        return intval( $post->post_author ) === intval( $user_id ) || current_user_can( 'manage_options' );
    }

    /**
     * Check if a user can delete a specific event
     */
    public static function user_can_delete_event( $event_id, $user_id = null ) {

        if ( ! $user_id ) {
            $user_id = get_current_user_id();
        }

        if ( ! $user_id ) {
            return false;
        }

        $post = get_post( $event_id );

        if ( ! $post || $post->post_type !== 'bpge_event' ) {
            return false;
        }

        // Author or admin
        return intval( $post->post_author ) === intval( $user_id ) || current_user_can( 'manage_options' );
    }
}
