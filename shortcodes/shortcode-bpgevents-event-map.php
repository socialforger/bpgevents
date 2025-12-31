<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Shortcode_Event_Map {

    public function __construct() {
        add_shortcode( 'bpgevents_map', array( $this, 'render' ) );
    }

    public function render( $atts ) {

        $atts = shortcode_atts(array(
            'id'   => 0,
            'zoom' => 14,
        ), $atts );

        $event_id = intval( $atts['id'] );

        if ( ! $event_id ) {
            return '<p>' . __( 'Invalid event ID.', 'bpgevents' ) . '</p>';
        }

        $coords = BPGEVENTS_Utils::get_coordinates( $event_id );

        if ( empty( $coords['lat'] ) || empty( $coords['lng'] ) ) {
            return '<p>' . __( 'This event does not have valid map coordinates.', 'bpgevents' ) . '</p>';
        }

        $map_id = 'bpge-map-' . $event_id;

        ob_start();
        ?>

        <div id="<?php echo esc_attr( $map_id ); ?>" class="bpge-map"></div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            BPGE_init_map(
                "<?php echo esc_js( $map_id ); ?>",
                <?php echo esc_js( $coords['lat'] ); ?>,
                <?php echo esc_js( $coords['lng'] ); ?>,
                <?php echo intval( $atts['zoom'] ); ?>,
                "<?php echo plugin_dir_url( dirname(__FILE__) ) . 'assets/img/marker-default.png'; ?>",
                "<?php echo plugin_dir_url( dirname(__FILE__) ) . 'assets/img/marker-shadow.png'; ?>"
            );
        });
        </script>

        <?php
        return ob_get_clean();
    }
}
