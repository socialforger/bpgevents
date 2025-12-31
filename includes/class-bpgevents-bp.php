<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_BP {

    public function __construct() {
        add_action( 'bp_groups_register_group_extension', array( $this, 'register_group_extension' ) );
    }

    /**
     * Estensione per scheda "Events" nei gruppi
     */
    public function register_group_extension() {

        if ( ! class_exists( 'BP_Group_Extension' ) ) {
            return;
        }

        class BPGEVENTS_Group_Extension extends BP_Group_Extension {

            public function __construct() {
                $args = array(
                    'slug'              => 'events',
                    'name'              => __( 'Events', 'bpgevents' ),
                    'nav_item_position' => 80,
                    'enable_nav_item'   => true,
                );

                parent::init( $args );
            }

            public function display( $group_id = null ) {
                // Template base: lista eventi del gruppo (puoi specializzarla in futuro)
                echo '<h2>' . esc_html__( 'Group Events', 'bpgevents' ) . '</h2>';
                echo do_shortcode( '[bpgevents_list]' );
            }
        }

        bp_register_group_extension( 'BPGEVENTS_Group_Extension' );
    }
}
