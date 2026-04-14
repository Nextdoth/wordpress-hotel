<?php
defined( 'ABSPATH' ) || exit;

/**
 * Frontend class — enqueues assets for the public-facing side.
 */
class Hotel_Booking_Public {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        if ( ! is_singular() && ! is_page() ) {
            return;
        }

        wp_enqueue_style( 'hb-public', HB_PLUGIN_URL . 'public/assets/css/public.css', [], HB_VERSION );
        wp_enqueue_script( 'hb-public', HB_PLUGIN_URL . 'public/assets/js/public.js', [ 'jquery' ], HB_VERSION, true );

        wp_localize_script( 'hb-public', 'hbPublic', [
            'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'hb_nonce' ),
            'currency' => get_option( 'hotel_booking_settings', [] )['currency_symbol'] ?? '€',
            'i18n'     => [
                'select_room'     => __( 'Please select a room.', 'hotel-booking' ),
                'fill_required'   => __( 'Please fill in all required fields.', 'hotel-booking' ),
                'invalid_email'   => __( 'Please enter a valid email address.', 'hotel-booking' ),
                'checking'        => __( 'Checking availability...', 'hotel-booking' ),
                'booking'         => __( 'Confirming your booking...', 'hotel-booking' ),
                'loading'         => __( 'Loading rooms...', 'hotel-booking' ),
                'no_rooms'        => __( 'No rooms available for the selected dates.', 'hotel-booking' ),
                'error'           => __( 'An error occurred. Please try again.', 'hotel-booking' ),
            ],
        ] );
    }
}
