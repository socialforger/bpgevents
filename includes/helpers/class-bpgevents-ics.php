<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_ICS {

    public static function download_ics( $event_id ) {

        $title = get_the_title( $event_id );

        header( 'Content-Type: text/calendar; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=event-' . $event_id . '.ics' );

        echo "BEGIN:VCALENDAR\n";
        echo "VERSION:2.0\n";
        echo "BEGIN:VEVENT\n";
        echo "SUMMARY:" . $title . "\n";
        echo "END:VEVENT\n";
        echo "END:VCALENDAR\n";

        exit;
    }
}
