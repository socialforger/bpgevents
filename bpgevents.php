<?php
/**
 * Plugin Name: Buddypress Groups Events
 * Description: Groups Events Management for Buddypress and BuddyBoss.
 * Version: 1.0.0
 * Author: Socialforger
 * Text Domain: bpgevents
 */

if ( ! defined( 'ABSPATH' ) ) exit;

final class BPGEVENTS_Plugin {

    /**
     * Singleton instance
     */
    private static $instance = null;

    /**
     * Plugin version
     */
    const VERSION = '1.0.0';

    /**
     * Get instance
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->define_constants();
        $this->load_textdomain();
        $this->register_autoloader();

        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Define plugin constants
     */
    private function define_constants() {
        define( 'BPGEVENTS_PLUGIN_FILE', __FILE__ );
        define( 'BPGEVENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'BPGEVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'BPGEVENTS_PLUGIN_VERSION', self::VERSION );
    }

    /**
     * Load textdomain
     */
    private function load_textdomain() {
        load_plugin_textdomain(
            'bpgevents',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/languages/'
        );
    }

    /**
     * Simple PSR-4-like autoloader for plugin classes
     */
    private function register_autoloader() {
        spl_autoload_register( function ( $class ) {

            if ( strpos( $class, 'BPGEVENTS_' ) !== 0 ) {
                return;
            }

            $class_slug = strtolower( str_replace( 'BPGEVENTS_', '', $class ) );
            $class_slug = str_replace( '_', '-', $class_slug );

            $paths = array(
                BPGEVENTS_PLUGIN_DIR . 'includes/class-bpgevents-' . $class_slug . '.php',
                BPGEVENTS_PLUGIN_DIR . 'includes/helpers/class-bpgevents-' . $class_slug . '.php',
                BPGEVENTS_PLUGIN_DIR . 'admin/class-bpgevents-' . $class_slug . '.php',
                BPGEVENTS_PLUGIN_DIR . 'shortcodes/shortcode-bpgevents-' . $class_slug . '.php',
                BPGEVENTS_PLUGIN_DIR . 'widgets/widget-bpgevents-' . $class_slug . '.php',
            );

            foreach ( $paths as $file ) {
                if ( file_exists( $file ) ) {
                    include $file;
                    return;
                }
            }
        } );
    }

    /**
     * Init plugin components
     */
    public function init() {

        // Core
        new BPGEVENTS_CPT();
        new BPGEVENTS_Meta();
        new BPGEVENTS_Maps();
        new BPGEVENTS_Permissions();
        new BPGEVENTS_Participation();
        new BPGEVENTS_API();
        new BPGEVENTS_API_My_Events();
        new BPGEVENTS_Notifications();
        new BPGEVENTS_Markers();
        new BPGEVENTS_Templates();

        // Admin
        if ( is_admin() ) {
            new BPGEVENTS_Settings();
        }

        // Shortcodes
        new BPGEVENTS_Shortcode_Events_List();
        new BPGEVENTS_Shortcode_My_Events();
        new BPGEVENTS_Shortcode_Event_Map();

        // Widgets
        add_action( 'widgets_init', function () {
            register_widget( 'BPGEVENTS_Widget_Upcoming_Events' );
            register_widget( 'BPGEVENTS_Widget_Events_Map' );
        } );

        // BuddyPress integration (if available)
        if ( function_exists( 'buddypress' ) ) {
            new BPGEVENTS_BP();
        }
    }

    /**
     * Front-end assets
     */
    public function enqueue_assets() {

        // Leaflet core
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

        // Leaflet MarkerCluster
        wp_enqueue_style(
            'leaflet-markercluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css',
            array(),
            '1.5.3'
        );

        wp_enqueue_style(
            'leaflet-markercluster-default',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css',
            array(),
            '1.5.3'
        );

        wp_enqueue_script(
            'leaflet-markercluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js',
            array( 'leaflet' ),
            '1.5.3',
            true
        );

        // Plugin CSS
        wp_enqueue_style(
            'bpgevents',
            BPGEVENTS_PLUGIN_URL . 'assets/css/bpgevents.css',
            array(),
            BPGEVENTS_PLUGIN_VERSION
        );

        // Plugin JS
        wp_enqueue_script(
            'bpgevents',
            BPGEVENTS_PLUGIN_URL . 'assets/js/bpgevents.js',
            array( 'jquery', 'leaflet', 'leaflet-markercluster' ),
            BPGEVENTS_PLUGIN_VERSION,
            true
        );

        wp_localize_script( 'bpgevents', 'BPGEVENTS', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'bpgevents_nonce' ),
        ) );
    }

    /**
     * Admin assets (if needed)
     */
    public function enqueue_admin_assets( $hook ) {
        // Per ora non carichiamo CSS/JS admin dedicati.
        // Potrai estendere qui se servirà.
    }
}

BPGEVENTS_Plugin::instance();
