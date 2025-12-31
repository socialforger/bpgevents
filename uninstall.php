<?php
/**
 * Uninstall script for Buddypress Groups Events
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Delete plugin options
 */
delete_option( 'bpgevents_settings' );
delete_option( 'bpgevents_default_marker_color' );
delete_option( 'bpgevents_enable_virtual' );
delete_option( 'bpgevents_enable_geocoding' );

/**
 * Delete event meta keys
 */
global $wpdb;

$meta_keys = array(
    'bpge_is_virtual',
    'bpge_city',
    'bpge_address',
    'bpge_province',
    'bpge_country',
    'bpge_virtual_url',
    'bpge_lat',
    'bpge_lng',
    'bpge_participants',
    'bpge_group_id'
);

foreach ( $meta_keys as $key ) {
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta} WHERE meta_key = %s",
            $key
        )
    );
}

/**
 * Delete user participation meta
 */
$wpdb->query(
    "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'bpgevents_participation_%'"
);

/**
 * Delete transients
 */
$wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_bpgevents_%'"
);

$wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_bpgevents_%'"
);
