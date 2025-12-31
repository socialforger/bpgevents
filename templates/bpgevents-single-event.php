<?php
/**
 * Single Event Template
 * This file overrides the single view for bpge_event
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) : the_post();

    $event_id = get_the_ID();
?>

<div class="bpgevents-single-event">

    <h1 class="bpgevents-title"><?php the_title(); ?></h1>

    <div class="bpgevents-meta-block">
        <?php BPGEVENTS_Templates::render_event_meta( $event_id ); ?>
    </div>

    <div class="bpgevents-content">
        <?php the_content(); ?>
    </div>

    <div class="bpgevents-participation">
        <?php BPGEVENTS_Templates::render_participation_button( $event_id ); ?>
    </div>

    <div class="bpgevents-map-section">
        <?php echo do_shortcode( '[bpgevents_event_map id="' . $event_id . '" height="350px"]' ); ?>
    </div>

    <div class="bpgevents-ics-download">
        <a class="bpgevents-ics-link"
           href="<?php echo esc_url( add_query_arg( array(
               'bpgevents_download_ics' => $event_id
           ), home_url() ) ); ?>">
            <?php _e( 'Download ICS File', 'bpgevents' ); ?>
        </a>
    </div>

</div>

<?php
endwhile;

get_footer();
