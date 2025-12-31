<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Utils {

    public static function is_virtual( $post_id ) {
        return (bool) get_post_meta( $post_id, '_bpge_virtual', true );
    }
}
