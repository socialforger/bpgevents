<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Widget_Upcoming_Events extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'bpgevents_upcoming_events',
            __( 'Upcoming Events', 'bpgevents' ),
            array( 'description' => __( 'Displays a list of upcoming events.', 'bpgevents' ) )
        );
    }

    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
        }

        $limit = ! empty( $instance['limit'] ) ? intval( $instance['limit'] ) : 5;

        $query = new WP_Query(array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => $limit,
            'orderby'        => 'date',
            'order'          => 'ASC',
        ));

        if ( ! $query->have_posts() ) {
            echo '<p>' . __( 'No upcoming events found.', 'bpgevents' ) . '</p>';
            echo $args['after_widget'];
            return;
        }

        echo '<ul class="bpge-widget-events">';

        while ( $query->have_posts() ) {
            $query->the_post();

            echo '<li>';
            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            echo '</li>';
        }

        echo '</ul>';

        wp_reset_postdata();

        echo $args['after_widget'];
    }

    public function form( $instance ) {

        $title = $instance['title'] ?? __( 'Upcoming Events', 'bpgevents' );
        $limit = $instance['limit'] ?? 5;
        ?>

        <p>
            <label><?php _e( 'Title:', 'bpgevents' ); ?></label>
            <input class="widefat" type="text"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label><?php _e( 'Number of events to show:', 'bpgevents' ); ?></label>
            <input class="tiny-text" type="number" min="1" step="1"
                   name="<?php echo $this->get_field_name('limit'); ?>"
                   value="<?php echo esc_attr( $limit ); ?>">
        </p>

        <?php
    }

    public function update( $new, $old ) {
        return array(
            'title' => sanitize_text_field( $new['title'] ),
            'limit' => intval( $new['limit'] ),
        );
    }
}
