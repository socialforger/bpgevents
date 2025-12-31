<?php
/**
 * Plugin Name: Buddypress Groups Events
 * Description: Groups Events Management for Buddypress and BuddyBoss.
 * Version: 1.0.0
 * Author: Socialforger
 * Text Domain: bpgevents
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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

        // Niente autoloader: includiamo tutto a mano.
        $this->include_files();

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
     * Include all required plugin files (no autoloader).
     */
    private function include_files() {

        // Core includes.
        $core_files = array(
            'includes/class-bpgevents-cpt.php',
            'includes/class-bpgevents-meta.php',
            'includes/class-bpgevents-maps.php',
            'includes/class-bpgevents-permissions.php',
            'includes/class-bpgevents-participation.php',
            'includes/class-bpgevents-api.php',
            'includes/class-bpgevents-api-my-events.php',
            'includes/class-bpgevents-notifications.php',
            'includes/class-bpgevents-markers.php',
            'includes/class-bpgevents-templates.php',
        );

        // BuddyPress/BuddyBoss integration (if file exists).
        $bp_file = 'includes/class-bpgevents-bp.php';

        // Admin.
        $admin_files = array(
            'admin/class-bpgevents-settings.php',
        );

        // Shortcodes.
        $shortcode_files = array(
            'shortcodes/shortcode-bpgevents-events-list.php',
            'shortcodes/shortcode-bpgevents-my-events.php',
            'shortcodes/shortcode-bpgevents-event-map.php',
        );

        // Widgets.
        $widget_files = array(
            'widgets/widget-bpgevents-upcoming-events.php',
            'widgets/widget-bpgevents-events-map.php',
        );

        $all_files = array_merge(
            $core_files,
            $admin_files,
            $shortcode_files,
            $widget_files
        );

        // Include core/admin/shortcodes/widgets.
        foreach ( $all_files as $relative_path ) {
            $file = BPGEVENTS_PLUGIN_DIR . $relative_path;
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }

        // Include BP integration only if il file esiste.
        $bp_full = BPGEVENTS_PLUGIN_DIR . $bp_file;
        if ( file_exists( $bp_full ) ) {
            require_once $bp_full;
        }
    }

    /**
     * Init plugin components
     */
    public function init() {

        // Core.
        if ( class_exists( 'BPGEVENTS_CPT' ) ) {
            new BPGEVENTS_CPT();
        }

        if ( class_exists( 'BPGEVENTS_Meta' ) ) {
            new BPGEVENTS_Meta();
        }

        if ( class_exists( 'BPGEVENTS_Maps' ) ) {
            new BPGEVENTS_Maps();
        }

        if ( class_exists( 'BPGEVENTS_Permissions' ) ) {
            new BPGEVENTS_Permissions();
        }

        if ( class_exists( 'BPGEVENTS_Participation' ) ) {
            new BPGEVENTS_Participation();
        }

        if ( class_exists( 'BPGEVENTS_API' ) ) {
            new BPGEVENTS_API();
        }

        if ( class_exists( 'BPGEVENTS_API_My_Events' ) ) {
            new BPGEVENTS_API_My_Events();
        }

        if ( class_exists( 'BPGEVENTS_Notifications' ) ) {
            new BPGEVENTS_Notifications();
        }

        if ( class_exists( 'BPGEVENTS_Markers' ) ) {
            new BPGEVENTS_Markers();
        }

        if ( class_exists( 'BPGEVENTS_Templates' ) ) {
            new BPGEVENTS_Templates();
        }

        // Admin.
        if ( is_admin() && class_exists( 'BPGEVENTS_Settings' ) ) {
            new BPGEVENTS_Settings();
        }

        // Shortcodes.
        if ( class_exists( 'BPGEVENTS_Shortcode_Events_List' ) ) {
            new BPGEVENTS_Shortcode_Events_List();
        }

        if ( class_exists( 'BPGEVENTS_Shortcode_My_Events' ) ) {
            new BPGEVENTS_Shortcode_My_Events();
        }

        if ( class_exists( 'BPGEVENTS_Shortcode_Event_Map' ) ) {
            new BPGEVENTS_Shortcode_Event_Map();
        }

        // Widgets.
        add_action( 'widgets_init', function () {
            if ( class_exists( 'BPGEVENTS_Widget_Upcoming_Events' ) ) {
                register_widget( 'BPGEVENTS_Widget_Upcoming_Events' );
            }
            if ( class_exists( 'BPGEVENTS_Widget_Events_Map' ) ) {
                register_widget( 'BPGEVENTS_Widget_Events_Map' );
            }
        } );

        // BuddyPress integration (if available and class exists).
        if ( function_exists( 'buddypress' ) && class_exists( 'BPGEVENTS_BP' ) ) {
            new BPGEVENTS_BP();
        }
    }

    /**
     * Front-end assets
     */
    public function enqueue_assets() {

        // Leaflet core.
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

        // Leaflet MarkerCluster.
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

        // Plugin CSS.
        wp_enqueue_style(
            'bpgevents',
            BPGEVENTS_PLUGIN_URL . 'assets/css/bpgevents.css',
            array(),
            BPGEVENTS_PLUGIN_VERSION
        );

        // Plugin JS.
        wp_enqueue_script(
            'bpgevents',
            BPGEVENTS_PLUGIN_URL . 'assets/js/bpgevents.js',
            array( 'jquery', 'leaflet', 'leaflet-markercluster' ),
            BPGEVENTS_PLUGIN_VERSION,
            true
        );

        wp_localize_script(
            'bpgevents',
            'BPGEVENTS',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'bpgevents_nonce' ),
            )
        );
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
