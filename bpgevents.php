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
 * Autoloader PSR‑4 semplificato
 */
spl_autoload_register( function( $class ) {

    if ( strpos( $class, 'BPGEVENTS_' ) !== 0 ) return;

    $base = plugin_dir_path( __FILE__ );
    $name = strtolower( str_replace( 'BPGEVENTS_', '', $class ) );
    $name = str_replace( '_', '-', $name );

    if ( strpos( $name, 'utils' ) !== false || strpos( $name, 'ics' ) !== false ) {
        $file = $base . 'includes/helpers/class-bpgevents-' . $name . '.php';
    } elseif ( strpos( $name, 'settings' ) !== false ) {
        $file = $base . 'admin/class-bpgevents-' . $name . '.php';
    } elseif ( strpos( $name, 'widget' ) !== false ) {
        $file = $base . 'widgets/widget-bpgevents-' . $name . '.php';
    } elseif ( strpos( $name, 'shortcode' ) !== false ) {
        $file = $base . 'shortcodes/shortcode-bpgevents-' . $name . '.php';
    } else {
        $file = $base . 'includes/class-bpgevents-' . $name . '.php';
    }

    if ( file_exists( $file ) ) require_once $file;
});

/**
 * Classe principale
 */
class BPGEVENTS {

    public function __construct() {

        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        // Core
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

        // BuddyPress
        if ( function_exists( 'buddypress' ) ) {
            new BPGEVENTS_BP();
        }

        // Admin
        if ( is_admin() ) {
            new BPGEVENTS_Settings();
        }

        // Shortcode
        new BPGEVENTS_Shortcode_Events_List();
        new BPGEVENTS_Shortcode_My_Events();
        new BPGEVENTS_Shortcode_Event_Map();

        // Widget
        new BPGEVENTS_Widget_Upcoming_Events();
        new BPGEVENTS_Widget_Events_Map();

        // Assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // ICS
        add_action( 'init', array( $this, 'handle_ics_download' ) );

        // Template override
        add_filter( 'template_include', array( $this, 'template_override' ) );
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            'bpgevents',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
    }

    public function enqueue_assets() {

        wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
        wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );

        wp_enqueue_style( 'bpgevents-css', plugin_dir_url( __FILE__ ) . 'assets/css/bpgevents.css', array(), '1.0.0' );
        wp_enqueue_script( 'bpgevents-js', plugin_dir_url( __FILE__ ) . 'assets/js/bpgevents.js', array(), '1.0.0', true );

        wp_localize_script( 'bpgevents-js', 'bpgevents_ajax', array(
            'ajax_url'           => admin_url( 'admin-ajax.php' ),
            'join_label'         => __( 'Join Event', 'bpgevents' ),
            'leave_label'        => __( 'Leave Event', 'bpgevents' ),
            'participants_label' => __( 'Participants: %d', 'bpgevents' ),
        ) );
    }

    public function handle_ics_download() {
        if ( isset( $_GET['bpgevents_download_ics'] ) ) {
            $event_id = intval( $_GET['bpgevents_download_ics'] );
            BPGEVENTS_ICS::download_ics( $event_id );
        }
    }

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
