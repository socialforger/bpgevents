<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Permissions {

    public static function can_join( $event_id ) {
        return is_user_logged_in();
    }

    public static function can_edit( $event_id ) {
        return current_user_can( 'edit_post', $event_id );
    }
}
