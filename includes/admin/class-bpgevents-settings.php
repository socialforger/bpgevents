<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Settings {

    private $option_group = 'bpgevents_settings_group';
    private $option_name  = 'bpgevents_settings';

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Add plugin settings page under "Settings"
     */
    public function add_settings_page() {

        add_options_page(
            __( 'BPGE Events Settings', 'bpgevents' ),
            __( 'BPGE Events', 'bpgevents' ),
            'manage_options',
            'bpgevents-settings',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Register settings and fields
     */
    public function register_settings() {

        register_setting(
            $this->option_group,
            $this->option_name,
            array( $this, 'sanitize_settings' )
        );

        add_settings_section(
            'bpgevents_main_section',
            __( 'General Settings', 'bpgevents' ),
            '__return_false',
            'bpgevents-settings'
        );

        add_settings_field(
            'default_marker_color',
            __( 'Default Marker Color', 'bpgevents' ),
            array( $this, 'field_default_marker_color' ),
            'bpgevents-settings',
            'bpgevents_main_section'
        );

        add_settings_field(
            'enable_virtual_events',
            __( 'Enable Virtual Events', 'bpgevents' ),
            array( $this, 'field_enable_virtual_events' ),
            'bpgevents-settings',
            'bpgevents_main_section'
        );

        add_settings_field(
            'enable_city_geocoding',
            __( 'Enable City Auto-Geocoding', 'bpgevents' ),
            array( $this, 'field_enable_city_geocoding' ),
            'bpgevents-settings',
            'bpgevents_main_section'
        );
    }

    /**
     * Sanitize settings before saving
     */
    public function sanitize_settings( $input ) {

        $output = array();

        $output['default_marker_color']   = sanitize_hex_color( $input['default_marker_color'] ?? '#ff0000' );
        $output['enable_virtual_events']  = ! empty( $input['enable_virtual_events'] ) ? 1 : 0;
        $output['enable_city_geocoding']  = ! empty( $input['enable_city_geocoding'] ) ? 1 : 0;

        return $output;
    }

    /**
     * Render: Default Marker Color
     */
    public function field_default_marker_color() {

        $options = get_option( $this->option_name );
        $value   = $options['default_marker_color'] ?? '#ff0000';

        echo '<input type="text" name="' . $this->option_name . '[default_marker_color]" 
                     value="' . esc_attr( $value ) . '" 
                     class="regular-text" />';
        echo '<p class="description">' .
             __( 'Choose the default color for map markers.', 'bpgevents' ) .
             '</p>';
    }

    /**
     * Render: Enable Virtual Events
     */
    public function field_enable_virtual_events() {

        $options = get_option( $this->option_name );
        $checked = ! empty( $options['enable_virtual_events'] ) ? 'checked' : '';

        echo '<label>';
        echo '<input type="checkbox" name="' . $this->option_name . '[enable_virtual_events]" ' . $checked . ' />';
        echo ' ' . __( 'Allow users to create virtual events.', 'bpgevents' );
        echo '</label>';
    }

    /**
     * Render: Enable City Auto-Geocoding
     */
    public function field_enable_city_geocoding() {

        $options = get_option( $this->option_name );
        $checked = ! empty( $options['enable_city_geocoding'] ) ? 'checked' : '';

        echo '<label>';
        echo '<input type="checkbox" name="' . $this->option_name . '[enable_city_geocoding]" ' . $checked . ' />';
        echo ' ' . __( 'Automatically geocode cities for virtual events without coordinates.', 'bpgevents' );
        echo '</label>';
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {

        echo '<div class="wrap">';
        echo '<h1>' . __( 'BPGE Events Settings', 'bpgevents' ) . '</h1>';

        echo '<form method="post" action="options.php">';

        settings_fields( $this->option_group );
        do_settings_sections( 'bpgevents-settings' );
        submit_button();

        echo '</form>';
        echo '</div>';
    }
}
