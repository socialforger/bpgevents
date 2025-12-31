<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Notifications {

    public function __construct() {
        add_action( 'bpgevents_event_created', array( $this, 'notify_event_created' ), 10, 2 );
        add_action( 'bpgevents_event_updated', array( $this, 'notify_event_updated' ), 10, 2 );
        add_action( 'bpgevents_user_participated', array( $this, 'notify_user_participated' ), 10, 2 );
    }

    /**
     * Notify admin when a new event is created
     */
    public function notify_event_created( $event_id, $user_id ) {

        $admin_email = get_option( 'admin_email' );
        $event_title = get_the_title( $event_id );
        $event_link  = get_permalink( $event_id );
        $user        = get_userdata( $user_id );

        $subject = sprintf(
            __( 'New Event Created: %s', 'bpgevents' ),
            $event_title
        );

        $message = sprintf(
            __( "A new event has been created.\n\nTitle: %s\nAuthor: %s\nLink: %s", 'bpgevents' ),
            $event_title,
            $user ? $user->display_name : 'Unknown',
            $event_link
        );

        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * Notify admin when an event is updated
     */
    public function notify_event_updated( $event_id, $user_id ) {

        $admin_email = get_option( 'admin_email' );
        $event_title = get_the_title( $event_id );
        $event_link  = get_permalink( $event_id );
        $user        = get_userdata( $user_id );

        $subject = sprintf(
            __( 'Event Updated: %s', 'bpgevents' ),
            $event_title
        );

        $message = sprintf(
            __( "An event has been updated.\n\nTitle: %s\nAuthor: %s\nLink: %s", 'bpgevents' ),
            $event_title,
            $user ? $user->display_name : 'Unknown',
            $event_link
        );

        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * Notify event author when a user participates
     */
    public function notify_user_participated( $event_id, $user_id ) {

        $event_author = get_post_field( 'post_author', $event_id );
        $author_email = get_the_author_meta( 'user_email', $event_author );

        if ( ! $author_email ) {
            return;
        }

        $event_title = get_the_title( $event_id );
        $event_link  = get_permalink( $event_id );
        $user        = get_userdata( $user_id );

        $subject = sprintf(
            __( 'New Participant for Your Event: %s', 'bpgevents' ),
            $event_title
        );

        $message = sprintf(
            __( "A user has joined your event.\n\nEvent: %s\nParticipant: %s\nLink: %s", 'bpgevents' ),
            $event_title,
            $user ? $user->display_name : 'Unknown',
            $event_link
        );

        wp_mail( $author_email, $subject, $message );
    }
}
