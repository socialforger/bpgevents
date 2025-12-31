<?php
/**
 * Plugin Name: Buddypress Groups Events
 * Description: Groups Events Management for Buddypress and BuddyBoss.
 * Version: 1.0.0
 * Author: Socialforger
 * Text Domain: bpgevents
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * PSR‑4 Autoloader
 */
spl_autoload_register( function( $class ) {

    if ( strpos( $class, 'BPGEVENTS_' ) !== 0 ) {
        return;
    }

    $base_dir = plugin_dir_path( __FILE__ );

    $class_name = strtolower( str_replace( 'BPGEVENTS_', '', $class ) );
    $class_name = str_replace( '_', '-', $class_name );

    // helpers
    if ( strpos( $class_name, 'utils' ) !== false || strpos( $class_name, 'ics' ) !== false ) {
        $file = $base_dir . 'includes/helpers/class-bpgevents-' . $class_name . '.php';
    }
    // admin
    elseif ( strpos( $class_name, 'settings' ) !== false ) {
        $file = $base_dir . 'admin/class-bpgevents-' . $class_name . '.php';
    }
    // widgets
    elseif ( strpos( $class_name, 'widget' ) !== false ) {
        $file = $base_dir . 'widgets/widget-bpgevents-' . $class_name . '.php';
    }
    // shortcodes
    elseif ( strpos( $class_name, 'shortcode' ) !== false ) {
        $file = $base_dir . 'shortcodes/shortcode-bpgevents-' . $class_name . '.php';
    }
    // everything else
    else {
        $file = $base_dir . 'includes/class-bpgevents-' . $class_name . '.php';
    }

    if ( file_exists( $file ) ) {
        require_once $file;
    }
});

/**
 * Main Plugin Class
 */
class BPGEVENTS {

    public function __construct() {

        // Load translations
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        // Core components
        new BPGEVENTS_CPT();
        new BPGEVENTS_Meta();
        new BPGEVENTS_Maps();
        new BPGEVENTS_API();
        new BPGEVENTS_API_My_Events();
        new BPGEVENTS_Permissions();
        new BPGEVENTS_Participation();
        new BPGEVENTS_Notifications();
        new BPGEVENTS_Markers();
        new BPGEVENTS_Templates();

        // BuddyPress integration
        if ( function_exists( 'buddypress' ) ) {
            new BPGEVENTS_BP();
        }

        // Admin settings
        if ( is_admin() ) {
            new BPGEVENTS_Settings();
        }

        // Shortcodes
        new BPGEVENTS_Shortcode_Events_List();
        new BPGEVENTS_Shortcode_My_Events();
        new BPGEVENTS_Shortcode_Event_Map();

        // Assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // ICS download
        add_action( 'init', array( $this, 'handle_ics_download' ) );

        // Template override
        add_filter( 'template_include', array( $this, 'template_override' ) );
    }

    /**
     * Load translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'bpgevents',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
    }

    /**
     * Enqueue CSS + JS
     */
    public function enqueue_assets() {

        // Leaflet
        wp_enqueue_style(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            array(),
            '1.9.4'
        );

        wp_enqueue_script(
            'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            array(),
            '1.9.4',
            true
        );

        // Plugin CSS
        wp_enqueue_style(
            'bpgevents-css',
            plugin_dir_url( __FILE__ ) . 'assets/css/bpgevents.css',
            array(),
            '1.0.0'
        );

        // Plugin JS
        wp_enqueue_script(
            'bpgevents-js',
            plugin_dir_url( __FILE__ ) . 'assets/js/bpgevents.js',
            array(),
            '1.0.0',
            true
        );

        wp_localize_script( 'bpgevents-js', 'bpgevents_ajax', array(
            'ajax_url'           => admin_url( 'admin-ajax.php' ),
            'join_label'         => __( 'Join Event', 'bpgevents' ),
            'leave_label'        => __( 'Leave Event', 'bpgevents' ),
            'participants_label' => __( 'Participants: %d', 'bpgevents' ),
        ) );
    }

    /**
     * ICS download handler
     */
    public function handle_ics_download() {

        if ( isset( $_GET['bpgevents_download_ics'] ) ) {
            $event_id = intval( $_GET['bpgevents_download_ics'] );
            BPGEVENTS_ICS::download_ics( $event_id );
        }
    }

    /**
     * Template override
     */
    public function template_override( $template ) {

        if ( is_singular( 'bpge_event' ) ) {
            $custom = plugin_dir_path( __FILE__ ) . 'templates/bpgevents-single-event.php';
            if ( file_exists( $custom ) ) return $custom;
        }

        if ( is_post_type_archive( 'bpge_event' ) ) {
            $custom = plugin_dir_path( __FILE__ ) . 'templates/bpgevents-archive.php';
            if ( file_exists( $custom ) ) return $custom;
        }

        return $template;
    }
}

new BPGEVENTS();
