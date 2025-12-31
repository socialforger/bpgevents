<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<div id="primary" class="content-area bpge-single-event">
    <main id="main" class="site-main">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-meta">
                    <p class="bpge-location">
                        <?php echo esc_html( BPGEVENTS_Utils::get_location_string( get_the_ID() ) ); ?>
                    </p>

                    <?php if ( BPGEVENTS_Utils::is_virtual( get_the_ID() ) ) : ?>
                        <?php $url = BPGEVENTS_Utils::get_virtual_url( get_the_ID() ); ?>
                        <?php if ( $url ) : ?>
                            <p class="bpge-virtual-link">
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
                                    <?php _e( 'Join virtual event', 'bpgevents' ); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <p class="bpge-participants-count">
                        <?php
                        $user_id = get_current_user_id();
                        $joined  = get_user_meta( $user_id, '_bpge_joined_events', true );
                        if ( ! is_array( $joined ) ) $joined = array();
                        $count = count( $joined );
                        printf( __( 'Participants: %d', 'bpgevents' ), $count );
                        ?>
                    </p>

                    <?php
                    // Pulsante join/leave base (puoi rifinirlo)
                    if ( is_user_logged_in() ) {
                        $joined = in_array( get_the_ID(), $joined, true );
                        $class  = $joined ? 'bpge-leave-event' : 'bpge-join-event';
                        $label  = $joined ? __( 'Leave Event', 'bpgevents' ) : __( 'Join Event', 'bpgevents' );
                        echo '<button class="' . esc_attr( $class ) . '" data-event="' . get_the_ID() . '">'
                             . esc_html( $label ) . '</button>';
                    }
                    ?>

                    <p class="bpge-ics-download">
                        <a href="<?php echo esc_url( add_query_arg( 'bpgevents_download_ics', get_the_ID() ) ); ?>">
                            <?php _e( 'Download iCal file', 'bpgevents' ); ?>
                        </a>
                    </p>

                    <?php
                    // Mappa evento
                    echo do_shortcode( '[bpgevents_map id="' . get_the_ID() . '"]' );
                    ?>
                </footer>

            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php
get_sidebar();
get_footer();
