<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Templates {

    public function __construct() {
        add_filter( 'template_include', array( $this, 'override_templates' ) );
        add_filter( 'single_template', array( $this, 'single_event_template' ) );
        add_filter( 'archive_template', array( $this, 'archive_event_template' ) );
    }

    /**
     * Override single event template
     */
    public function single_event_template( $template ) {

        if ( is_singular( 'bpge_event' ) ) {
            $custom = BPGEV_PATH . 'templates/bpgevents-single-event.php';
            if ( file_exists( $custom ) ) {
                return $custom;
            }
        }

        return $template;
    }

    /**
     * Override archive template
     */
    public function archive_event_template( $template ) {

        if ( is_post_type_archive( 'bpge_event' ) ) {
            $custom = BPGEV_PATH . 'templates/bpgevents-archive.php';
            if ( file_exists( $custom ) ) {
                return $custom;
            }
        }

        return $template;
    }

    /**
     * Override BuddyPress group event templates if needed
     */
    public function override_templates( $template ) {

        if ( function_exists( 'bp_is_groups_component' ) && bp_is_groups_component() ) {

            if ( bp_is_current_action( 'events' ) ) {

                $custom = BPGEV_PATH . 'templates/bpgevents-group-events.php';

                if ( file_exists( $custom ) ) {
                    return $custom;
                }
            }
        }

        return $template;
    }

    /**
     * Render event meta block (used in templates)
     */
    public static function render_event_meta( $event_id ) {

        $is_virtual  = get_post_meta( $event_id, 'bpge_is_virtual', true );
        $address     = get_post_meta( $event_id, 'bpge_address', true );
        $city        = get_post_meta( $event_id, 'bpge_city', true );
        $province    = get_post_meta( $event_id, 'bpge_province', true );
        $country     = get_post_meta( $event_id, 'bpge_country', true );
        $virtual_url = get_post_meta( $event_id, 'bpge_virtual_url', true );

        echo '<div class="bpgevents-meta">';

        if ( $is_virtual ) {

            echo '<p><strong>' . __( 'Event Type:', 'bpgevents' ) . '</strong> ' . __( 'Virtual Event', 'bpgevents' ) . '</p>';

            if ( $virtual_url ) {
                echo '<p><strong>' . __( 'Join Link:', 'bpgevents' ) . '</strong> ';
                echo '<a href="' . esc_url( $virtual_url ) . '" target="_blank" rel="noopener">';
                echo esc_html( $virtual_url );
                echo '</a></p>';
            }

        } else {

            echo '<p><strong>' . __( 'Event Type:', 'bpgevents' ) . '</strong> ' . __( 'Presential Event', 'bpgevents' ) . '</p>';

            if ( $address ) {
                echo '<p><strong>' . __( 'Address:', 'bpgevents' ) . '</strong> ' . esc_html( $address ) . '</p>';
            }

            if ( $city ) {
                echo '<p><strong>' . __( 'City:', 'bpgevents' ) . '</strong> ' . esc_html( $city ) . '</p>';
            }

            if ( $province ) {
                echo '<p><strong>' . __( 'Province:', 'bpgevents' ) . '</strong> ' . esc_html( $province ) . '</p>';
            }

            if ( $country ) {
                echo '<p><strong>' . __( 'Country:', 'bpgevents' ) . '</strong> ' . esc_html( $country ) . '</p>';
            }
        }

        echo '</div>';
    }

    /**
     * Render participation button
     */
    public static function render_participation_button( $event_id ) {

        if ( ! is_user_logged_in() ) {
            echo '<p class="bpgevents-login-msg">' .
                 __( 'Log in to participate in this event.', 'bpgevents' ) .
                 '</p>';
            return;
        }

        $is_participating = BPGEVENTS_Participation::is_participating( $event_id );
        $count            = BPGEVENTS_Participation::get_participant_count( $event_id );

        echo '<button class="bpgevents-participation-btn" 
                     data-event-id="' . esc_attr( $event_id ) . '">';

        echo $is_participating
            ? __( 'Leave Event', 'bpgevents' )
            : __( 'Join Event', 'bpgevents' );

        echo '</button>';

        echo '<p class="bpgevents-participants-count">';
        echo sprintf(
            __( 'Participants: %d', 'bpgevents' ),
            intval( $count )
        );
        echo '</p>';
    }
}
