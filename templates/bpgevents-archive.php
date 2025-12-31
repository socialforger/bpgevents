<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<div id="primary" class="content-area bpge-archive-events">
    <main id="main" class="site-main">

        <header class="page-header">
            <h1 class="page-title"><?php _e( 'Events', 'bpgevents' ); ?></h1>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="bpge-events-list">

                <?php while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'bpge-event-box' ); ?>>

                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

                        <p class="bpge-location">
                            <?php echo esc_html( BPGEVENTS_Utils::get_location_string( get_the_ID() ) ); ?>
                        </p>

                        <div class="entry-excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <?php the_posts_pagination(); ?>

        <?php else : ?>

            <p><?php _e( 'No events found.', 'bpgevents' ); ?></p>

        <?php endif; ?>

    </main>
</div>

<?php
get_sidebar();
get_footer();
