<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ICS file generator for BPGE Events
 */
class BPGEVENTS_ICS {

    /**
     * Generate ICS content for an event
     */
    public static function generate_ics( $event_id ) {

        $event = get_post( $event_id );

        if ( ! $event || $event->post_type !== 'bpge_event' ) {
            return '';
        }

        $title       = get_the_title( $event_id );
        $description = wp_strip_all_tags( $event->post_content );
        $url         = get_permalink( $event_id );

        $is_virtual  = get_post_meta( $event_id, 'bpge_is_virtual', true );
        $address     = get_post_meta( $event_id, 'bpge_address', true );
        $city        = get_post_meta( $event_id, 'bpge_city', true );
        $province    = get_post_meta( $event_id, 'bpge_province', true );
        $country     = get_post_meta( $event_id, 'bpge_country', true );
        $virtual_url = get_post_meta( $event_id, 'bpge_virtual_url', true );

        // Build location string
        if ( $is_virtual ) {
            $location = $virtual_url ? $virtual_url : __( 'Virtual Event', 'bpgevents' );
        } else {
            $location_parts = array_filter( array( $address, $city, $province, $country ) );
            $location = implode( ', ', $location_parts );
        }

        // Event date (fallback to post date)
        $timestamp = strtotime( $event->post_date_gmt );
        $dtstart   = gmdate( 'Ymd\THis\Z', $timestamp );
        $dtend     = gmdate( 'Ymd\THis\Z', $timestamp + 3600 ); // default 1 hour

        $ics  = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//BPGE Events//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";

        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . uniqid() . "@bpgevents\r\n";
        $ics .= "DTSTAMP:" . gmdate( 'Ymd\THis\Z' ) . "\r\n";
        $ics .= "DTSTART:$dtstart\r\n";
        $ics .= "DTEND:$dtend\r\n";
        $ics .= "SUMMARY:" . self::escape( $title ) . "\r\n";
        $ics .= "DESCRIPTION:" . self::escape( $description ) . "\\n" . self::escape( $url ) . "\r\n";
        $ics .= "LOCATION:" . self::escape( $location ) . "\r\n";
        $ics .= "URL:" . self::escape( $url ) . "\r\n";
        $ics .= "END:VEVENT\r\n";

        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }

    /**
     * Force ICS-safe escaping
     */
    private static function escape( $string ) {
        $string = str_replace( "\\", "\\\\", $string );
        $string = str_replace( ";", "\\;", $string );
        $string = str_replace( ",", "\\,", $string );
        $string = str_replace( "\n", "\\n", $string );
        return $string;
    }

    /**
     * Force download of ICS file
     */
    public static function download_ics( $event_id ) {

        $ics = self::generate_ics( $event_id );

        if ( empty( $ics ) ) {
            wp_die( __( 'Unable to generate ICS file.', 'bpgevents' ) );
        }

        header( 'Content-Type: text/calendar; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=event-' . $event_id . '.ics' );

        echo $ics;
        exit;
    }
}
