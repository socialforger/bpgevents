<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widget: Events Map
 * Displays a Leaflet map with all events plotted as markers
 */
class BPGEVENTS_Widget_Map extends WP_Widget {

    public function __construct() {

        parent::__construct(
            'bpgevents_map',
            __( 'BPGE Events – Map', 'bpgevents' ),
            array(
                'description' => __( 'Displays a map with all events.', 'bpgevents' )
            )
        );
    }

    /**
     * Frontend display
     */
    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        $title = ! empty( $instance['title'] )
            ? apply_filters( 'widget_title', $instance['title'] )
            : __( 'Events Map', 'bpgevents' );

        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }

        $height = ! empty( $instance['height'] ) ? $instance['height'] : '300px';

        // Unique map ID
        $map_id = 'bpgevents-map-widget-' . wp_generate_password( 6, false, false );

        echo '<div id="' . esc_attr( $map_id ) . '" 
                  class="bpgevents-map-widget" 
                  style="width:100%; height:' . esc_attr( $height ) . ';"></div>';

        // Fetch all events
        $events = get_posts( array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ) );

        $markers = array();

        foreach ( $events as $event ) {

            $lat = get_post_meta( $event->ID, 'bpge_lat', true );
            $lng = get_post_meta( $event->ID, 'bpge_lng', true );
            $city = get_post_meta( $event->ID, 'bpge_city', true );
            $is_virtual = get_post_meta( $event->ID, 'bpge_is_virtual', true );

            // Virtual fallback: geocode city
            if ( $is_virtual && ( empty( $lat ) || empty( $lng ) ) ) {
                $coords = BPGEVENTS_Utils::get_city_coordinates( $city );
                if ( $coords ) {
                    $lat = $coords['lat'];
                    $lng = $coords['lng'];
                }
            }

            if ( empty( $lat ) || empty( $lng ) ) {
                continue;
            }

            $markers[] = array(
                'id'    => $event->ID,
                'title' => get_the_title( $event->ID ),
                'lat'   => $lat,
                'lng'   => $lng,
                'icon'  => BPGEVENTS_Markers::get_marker_for_event( $event->ID ),
                'link'  => get_permalink( $event->ID ),
            );
        }

        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {

            var map = L.map('<?php echo esc_js( $map_id ); ?>').setView([41.9028, 12.4964], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var markers = <?php echo wp_json_encode( $markers ); ?>;

            markers.forEach(function(event) {

                var icon = L.icon({
                    iconUrl: event.icon,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                });

                L.marker([event.lat, event.lng], { icon: icon })
                    .addTo(map)
                    .bindPopup(
                        '<a href="' + event.link + '">' + event.title + '</a>'
                    );
            });
        });
        </script>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Backend form
     */
    public function form( $instance ) {

        $title  = $instance['title'] ?? __( 'Events Map', 'bpgevents' );
        $height = $instance['height'] ?? '300px';
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'bpgevents' ); ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   type="text"
                   value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>">
                <?php _e( 'Map Height (e.g. 300px):', 'bpgevents' ); ?>
            </label>
            <input class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>"
                   type="text"
                   value="<?php echo esc_attr( $height ); ?>">
        </p>

        <?php
    }

    /**
     * Save widget settings
     */
    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['title']  = sanitize_text_field( $new_instance['title'] );
        $instance['height'] = sanitize_text_field( $new_instance['height'] );

        return $instance;
    }
}

/**
 * Register widget
 */
function bpgevents_register_map_widget() {
    register_widget( 'BPGEVENTS_Widget_Map' );
}
add_action( 'widgets_init', 'bpgevents_register_map_widget' );
