<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Settings {

    private $option_name = 'bpgevents_settings';

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Aggiunge la pagina nel menu Impostazioni
     */
    public function add_menu() {
        add_options_page(
            __( 'BPGE Events Settings', 'bpgevents' ),
            __( 'BPGE Events', 'bpgevents' ),
            'manage_options',
            'bpgevents-settings',
            array( $this, 'render_page' )
        );
    }

    /**
     * Registra le impostazioni
     */
    public function register_settings() {

        register_setting(
            'bpgevents_settings_group',
            $this->option_name,
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'bpgevents_general_section',
            __( 'General Settings', 'bpgevents' ),
            '__return_false',
            'bpgevents-settings'
        );

        add_settings_field(
            'marker_color',
            __( 'Default Marker Color', 'bpgevents' ),
            array( $this, 'field_marker_color' ),
            'bpgevents-settings',
            'bpgevents_general_section'
        );

        add_settings_field(
            'enable_virtual',
            __( 'Enable Virtual Events', 'bpgevents' ),
            array( $this, 'field_enable_virtual' ),
            'bpgevents-settings',
            'bpgevents_general_section'
        );

        add_settings_field(
            'enable_geocoding',
            __( 'Enable City Auto-Geocoding', 'bpgevents' ),
            array( $this, 'field_enable_geocoding' ),
            'bpgevents-settings',
            'bpgevents_general_section'
        );
    }

    /**
     * Sanitizzazione dei dati
     */
    public function sanitize( $input ) {

        return array(
            'marker_color'     => sanitize_hex_color( $input['marker_color'] ?? '#2d8cff' ),
            'enable_virtual'   => ! empty( $input['enable_virtual'] ) ? 1 : 0,
            'enable_geocoding' => ! empty( $input['enable_geocoding'] ) ? 1 : 0,
        );
    }

    /**
     * Campo: colore marker
     */
    public function field_marker_color() {
        $options = get_option( $this->option_name );
        $value   = $options['marker_color'] ?? '#2d8cff';

        echo '<input type="color" name="' . $this->option_name . '[marker_color]" value="' . esc_attr( $value ) . '">';
        echo '<p class="description">' . __( 'Choose the default color for map markers.', 'bpgevents' ) . '</p>';
    }

    /**
     * Campo: abilita eventi virtuali
     */
    public function field_enable_virtual() {
        $options = get_option( $this->option_name );
        $checked = ! empty( $options['enable_virtual'] ) ? 'checked' : '';

        echo '<label><input type="checkbox" name="' . $this->option_name . '[enable_virtual]" ' . $checked . '> ';
        echo __( 'Enable Virtual Events', 'bpgevents' ) . '</label>';
    }

    /**
     * Campo: geocoding automatico
     */
    public function field_enable_geocoding() {
        $options = get_option( $this->option_name );
        $checked = ! empty( $options['enable_geocoding'] ) ? 'checked' : '';

        echo '<label><input type="checkbox" name="' . $this->option_name . '[enable_geocoding]" ' . $checked . '> ';
        echo __( 'Enable City Auto-Geocoding', 'bpgevents' ) . '</label>';
    }

    /**
     * Render della pagina impostazioni
     */
    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'BPGE Events Settings', 'bpgevents' ); ?></h1>

            <?php if ( isset($_GET['settings-updated']) ) : ?>
                <div class="updated notice"><p><?php _e( 'Settings saved.', 'bpgevents' ); ?></p></div>
            <?php endif; ?>

            <form method="post" action="options.php">
                <?php
                    settings_fields( 'bpgevents_settings_group' );
                    do_settings_sections( 'bpgevents-settings' );
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
