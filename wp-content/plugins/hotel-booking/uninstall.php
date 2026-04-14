<?php
/**
 * Fired when the plugin is uninstalled.
 * Drops all custom database tables and removes plugin options.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hb_reservations" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hb_rates" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hb_blocked_dates" );

delete_option( 'hotel_booking_settings' );
delete_option( 'hb_db_version' );
