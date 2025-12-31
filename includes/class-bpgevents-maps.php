<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Widget_Events_Map extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'bpgevents_events_map',
            __( 'Events Map', 'bpgevents' ),
            array( 'description' => __( 'Displays a map with all events.', 'bpgevents' ) )
        );
    }

    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
        }

        $height = $instance['height'] ?? '300px';
        $map_id = 'bpge-map-widget-' . $this->id;

        echo '<div id="' . esc_attr( $map_id ) . '" class="bpge-map" style="height:' . esc_attr( $height ) . ';"></div>';

        // Get all events with coordinates
        $query = new WP_Query(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
        ));

        $markers = array();

        foreach ( $query->posts as $post ) {
            $coords = BPGEVENTS_Utils::get_coordinates( $post->ID );

            if ( ! empty( $coords['lat'] ) && ! empty( $coords['lng'] ) ) {
                $markers[] = array(
                    'title' => get_the_title( $post->ID ),
                    'lat'   => (float) $coords['lat'],
                    'lng'   => (float) $coords['lng'],
                    'url'   => get_permalink( $post->ID ),
                );
            }
        }

        wp_reset_postdata();
        ?>

        <script>
        document.addEventListener("DOMContentLoaded", function() {

            var map = L.map("<?php echo esc_js( $map_id ); ?>").setView([0, 0], 2);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var markersData = <?php echo json_encode( $markers ); ?>;

            // Cluster group
            var clusterGroup = L.markerClusterGroup();

            markersData.forEach(function(item){
                var marker = L.marker([item.lat, item.lng]);
                marker.bindPopup('<a href="' + item.url + '">' + item.title + '</a>');
                clusterGroup.addLayer(marker);
            });

            map.addLayer(clusterGroup);

            if (markersData.length > 0) {
                map.fitBounds(clusterGroup.getBounds());
            }
        });
        </script>

        <?php

        echo $args['after_widget'];
    }

    public function form( $instance ) {

        $title  = $instance['title'] ?? __( 'Events Map', 'bpgevents' );
        $height = $instance['height'] ?? '300px';
        ?>

        <p>
            <label><?php _e( 'Title:', 'bpgevents' ); ?></label>
            <input class="widefat" type="text"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label><?php _e( 'Map Height (e.g. 300px):', 'bpgevents' ); ?></label>
            <input class="widefat" type="text"
                   name="<?php echo $this->get_field_name('height'); ?>"
                   value="<?php echo esc_attr( $height ); ?>">
        </p>

        <?php
    }

    public function update( $new, $old ) {
        return array(
            'title'  => sanitize_text_field( $new['title'] ?? '' ),
            'height' => sanitize_text_field( $new['height'] ?? '300px' ),
        );
    }
}
