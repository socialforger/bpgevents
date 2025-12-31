<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Shortcode_Events_List {

    public function __construct() {
        add_shortcode( 'bpgevents_list', array( $this, 'render' ) );
    }

    public function render( $atts ) {

        $query = new WP_Query(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ));

        if ( ! $query->have_posts() ) {
            return '<p>' . __( 'No events available.', 'bpgevents' ) . '</p>';
        }

        ob_start();

        echo '<div class="bpge-events-list">';

        while ( $query->have_posts() ) {
            $query->the_post();

            echo '<div class="bpge-event-box">';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '<p>' . BPGEVENTS_Utils::get_location_string( get_the_ID() ) . '</p>';
            echo '</div>';
        }

        echo '</div>';

        wp_reset_postdata();

        return ob_get_clean();
    }
}
