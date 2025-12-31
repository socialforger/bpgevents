<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widget: Upcoming Events
 */
class BPGEVENTS_Widget_Upcoming_Events extends WP_Widget {

    public function __construct() {

        parent::__construct(
            'bpgevents_upcoming_events',
            __( 'BPGE Events – Upcoming Events', 'bpgevents' ),
            array(
                'description' => __( 'Displays a list of upcoming events.', 'bpgevents' )
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
            : __( 'Upcoming Events', 'bpgevents' );

        if ( $title ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        }

        $limit = ! empty( $instance['limit'] ) ? intval( $instance['limit'] ) : 5;

        $events = get_posts( array(
            'post_type'      => 'bpge_event',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'ASC',
        ) );

        if ( empty( $events ) ) {
            echo '<p>' . __( 'No upcoming events found.', 'bpgevents' ) . '</p>';
            echo $args['after_widget'];
            return;
        }

        echo '<ul class="bpgevents-upcoming-events">';

        foreach ( $events as $event ) {

            $is_virtual = get_post_meta( $event->ID, 'bpge_is_virtual', true );
            $city       = get_post_meta( $event->ID, 'bpge_city', true );

            echo '<li class="bpgevents-upcoming-event-item">';

            echo '<a href="' . esc_url( get_permalink( $event->ID ) ) . '">';
            echo esc_html( get_the_title( $event->ID ) );
            echo '</a>';

            echo '<div class="bpgevents-upcoming-meta">';

            echo $is_virtual
                ? '<span class="bpgevents-tag bpgevents-tag-virtual">' . __( 'Virtual', 'bpgevents' ) . '</span>'
                : '<span class="bpgevents-tag bpgevents-tag-presential">' . __( 'Presential', 'bpgevents' ) . '</span>';

            if ( $city ) {
                echo '<span class="bpgevents-city">' . esc_html( $city ) . '</span>';
            }

            echo '</div>';

            echo '</li>';
        }

        echo '</ul>';

        echo $args['after_widget'];
    }

    /**
     * Backend form
     */
    public function form( $instance ) {

        $title = $instance['title'] ?? __( 'Upcoming Events', 'bpgevents' );
        $limit = $instance['limit'] ?? 5;
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
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
                <?php _e( 'Number of events to show:', 'bpgevents' ); ?>
            </label>
            <input class="tiny-text"
                   id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>"
                   type="number"
                   step="1"
                   min="1"
                   value="<?php echo esc_attr( $limit ); ?>">
        </p>

        <?php
    }

    /**
     * Save widget settings
     */
    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['limit'] = intval( $new_instance['limit'] );

        return $instance;
    }
}

/**
 * Register widget
 */
function bpgevents_register_upcoming_events_widget() {
    register_widget( 'BPGEVENTS_Widget_Upcoming_Events' );
}
add_action( 'widgets_init', 'bpgevents_register_upcoming_events_widget' );
