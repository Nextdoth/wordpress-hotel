<?php
/**
 * Plugin Name: Hotel Booking
 * Plugin URI:  https://www.rhein-hotel-krone.de/
 * Description: Premium hotel room booking and reservation management system for Rhein-Hotel Krone.
 * Version:     1.0.0
 * Author:      Rhein-Hotel Krone
 * Author URI:  https://www.rhein-hotel-krone.de/
 * Text Domain: hotel-booking
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License:     GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

// Constants
define( 'HB_VERSION',     '1.0.0' );
define( 'HB_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'HB_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'HB_PLUGIN_FILE', __FILE__ );
define( 'HB_DB_VERSION',  '1.0' );

// Autoload includes
require_once HB_PLUGIN_DIR . 'includes/class-hotel-booking-db.php';
require_once HB_PLUGIN_DIR . 'includes/class-hotel-booking-post-types.php';
require_once HB_PLUGIN_DIR . 'includes/class-hotel-booking-reservation.php';
require_once HB_PLUGIN_DIR . 'includes/class-hotel-booking-email.php';
require_once HB_PLUGIN_DIR . 'includes/class-hotel-booking-shortcodes.php';
require_once HB_PLUGIN_DIR . 'includes/class-hotel-booking.php';

if ( is_admin() ) {
    require_once HB_PLUGIN_DIR . 'admin/class-hotel-booking-admin.php';
} else {
    require_once HB_PLUGIN_DIR . 'public/class-hotel-booking-public.php';
}

// Activation / Deactivation hooks
register_activation_hook( __FILE__, [ 'Hotel_Booking_DB', 'install' ] );
register_deactivation_hook( __FILE__, [ 'Hotel_Booking_DB', 'deactivate' ] );

/**
 * Returns the main plugin instance.
 */
function hotel_booking() {
    return Hotel_Booking::get_instance();
}

// Boot
add_action( 'plugins_loaded', 'hotel_booking' );
