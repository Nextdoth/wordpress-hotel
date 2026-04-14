<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main Hotel Booking plugin class. Singleton.
 */
class Hotel_Booking {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'init', [ 'Hotel_Booking_Post_Types', 'register' ] );
        add_action( 'init', [ 'Hotel_Booking_Shortcodes', 'register' ] );

        // AJAX — check availability
        add_action( 'wp_ajax_hb_check_availability',        [ $this, 'ajax_check_availability' ] );
        add_action( 'wp_ajax_nopriv_hb_check_availability', [ $this, 'ajax_check_availability' ] );

        // AJAX — create booking
        add_action( 'wp_ajax_hb_create_booking',        [ $this, 'ajax_create_booking' ] );
        add_action( 'wp_ajax_nopriv_hb_create_booking', [ $this, 'ajax_create_booking' ] );

        if ( is_admin() ) {
            Hotel_Booking_Admin::get_instance();
        } else {
            Hotel_Booking_Public::get_instance();
        }
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            'hotel-booking',
            false,
            dirname( plugin_basename( HB_PLUGIN_FILE ) ) . '/languages/'
        );
    }

    /**
     * AJAX: Check room availability for given dates.
     */
    public function ajax_check_availability() {
        check_ajax_referer( 'hb_nonce', 'nonce' );

        $check_in  = sanitize_text_field( $_POST['check_in'] ?? '' );
        $check_out = sanitize_text_field( $_POST['check_out'] ?? '' );
        $adults    = absint( $_POST['adults'] ?? 1 );
        $children  = absint( $_POST['children'] ?? 0 );

        if ( ! $check_in || ! $check_out ) {
            wp_send_json_error( [ 'message' => __( 'Please select check-in and check-out dates.', 'hotel-booking' ) ] );
        }

        if ( strtotime( $check_in ) >= strtotime( $check_out ) ) {
            wp_send_json_error( [ 'message' => __( 'Check-out must be after check-in.', 'hotel-booking' ) ] );
        }

        if ( strtotime( $check_in ) < strtotime( 'today' ) ) {
            wp_send_json_error( [ 'message' => __( 'Check-in date cannot be in the past.', 'hotel-booking' ) ] );
        }

        $nights = (int) ( ( strtotime( $check_out ) - strtotime( $check_in ) ) / DAY_IN_SECONDS );
        $rooms  = Hotel_Booking_Reservation::get_available_rooms( $check_in, $check_out, $adults );

        if ( empty( $rooms ) ) {
            wp_send_json_error( [ 'message' => __( 'No rooms available for the selected dates.', 'hotel-booking' ) ] );
        }

        wp_send_json_success( [
            'rooms'     => $rooms,
            'nights'    => $nights,
            'check_in'  => $check_in,
            'check_out' => $check_out,
            'adults'    => $adults,
            'children'  => $children,
        ] );
    }

    /**
     * AJAX: Create a new booking (instant confirmation).
     */
    public function ajax_create_booking() {
        check_ajax_referer( 'hb_nonce', 'nonce' );

        $room_id    = absint( $_POST['room_id'] ?? 0 );
        $check_in   = sanitize_text_field( $_POST['check_in'] ?? '' );
        $check_out  = sanitize_text_field( $_POST['check_out'] ?? '' );
        $adults     = absint( $_POST['adults'] ?? 1 );
        $children   = absint( $_POST['children'] ?? 0 );
        $name       = sanitize_text_field( $_POST['guest_name'] ?? '' );
        $email      = sanitize_email( $_POST['guest_email'] ?? '' );
        $phone      = sanitize_text_field( $_POST['guest_phone'] ?? '' );
        $notes      = sanitize_textarea_field( $_POST['notes'] ?? '' );
        $lang       = in_array( $_POST['lang'] ?? 'en', [ 'en', 'de' ], true ) ? $_POST['lang'] : 'en';

        // Validate required
        if ( ! $room_id || ! $check_in || ! $check_out || ! $name || ! $email ) {
            wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'hotel-booking' ) ] );
        }

        if ( ! is_email( $email ) ) {
            wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'hotel-booking' ) ] );
        }

        // Verify availability
        if ( ! Hotel_Booking_DB::is_room_available( $room_id, $check_in, $check_out ) ) {
            wp_send_json_error( [ 'message' => __( 'Sorry, this room is no longer available for the selected dates.', 'hotel-booking' ) ] );
        }

        $nights      = (int) ( ( strtotime( $check_out ) - strtotime( $check_in ) ) / DAY_IN_SECONDS );
        $price_night = Hotel_Booking_DB::get_room_price( $room_id, $check_in );
        $total       = $price_night * $nights;
        $reference   = Hotel_Booking_Reservation::generate_reference();

        $result = Hotel_Booking_Reservation::create( [
            'room_id'    => $room_id,
            'guest_name' => $name,
            'guest_email'=> $email,
            'guest_phone'=> $phone,
            'check_in'   => $check_in,
            'check_out'  => $check_out,
            'adults'     => $adults,
            'children'   => $children,
            'total_price'=> $total,
            'status'     => 'confirmed',
            'reference'  => $reference,
            'notes'      => $notes,
            'lang'       => $lang,
        ] );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }

        // Send emails
        $reservation = Hotel_Booking_DB::get_reservation_by_reference( $reference );
        Hotel_Booking_Email::send_guest_confirmation( $reservation );
        Hotel_Booking_Email::send_admin_notification( $reservation );

        wp_send_json_success( [
            'reference' => $reference,
            'message'   => __( 'Your booking is confirmed! A confirmation email has been sent.', 'hotel-booking' ),
            'redirect'  => add_query_arg( 'booking_ref', $reference, get_permalink( get_page_by_path( 'booking-confirmation' ) ) ),
        ] );
    }
}
