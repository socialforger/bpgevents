<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Markers {

    /**
     * URL marker standard
     */
    public static function get_default_marker() {
        return plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/marker-default.png';
    }

    /**
     * URL shadow marker
     */
    public static function get_default_shadow() {
        return plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/marker-shadow.png';
    }

    /**
     * URL icona evento virtuale
     */
    public static function get_virtual_icon() {
        return plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/icon-virtual.png';
    }

    /**
     * URL icona evento presenziale
     */
    public static function get_presential_icon() {
        return plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/icon-presential.png';
    }
}
