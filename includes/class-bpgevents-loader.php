<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BPGEVENTS_Loader {

    public function __construct() {
        $this->load_dependencies();
        $this->init_classes();
    }

    private function load_dependencies() {

        require_once BPGEV_PATH . 'includes/class-bpgevents-cpt.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-meta.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-maps.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-api.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-api-my-events.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-permissions.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-participation.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-notifications.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-markers.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-templates.php';
        require_once BPGEV_PATH . 'includes/class-bpgevents-bp.php';

        require_once BPGEV_PATH . 'includes/helpers/class-bpgevents-utils.php';
        require_once BPGEV_PATH . 'includes/helpers/class-bpgevents-ics.php';

        require_once BPGEV_PATH . 'includes/admin/class-bpgevents-settings.php';
    }

    private function init_classes() {

        new BPGEVENTS_CPT();
        new BPGEVENTS_Meta();
        new BPGEVENTS_Maps();
        new BPGEVENTS_API();
        new BPGEVENTS_API_My_Events();
        new BPGEVENTS_Permissions();
        new BPGEVENTS_Participation();
        new BPGEVENTS_Notifications();
        new BPGEVENTS_Markers();
        new BPGEVENTS_Templates();
        new BPGEVENTS_BP();
        new BPGEVENTS_Settings();
    }
}
