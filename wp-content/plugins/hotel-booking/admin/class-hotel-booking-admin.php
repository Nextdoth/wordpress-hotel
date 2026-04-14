<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class — menus, pages, AJAX handlers for admin.
 */
class Hotel_Booking_Admin {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu',            [ $this, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_ajax_hb_admin_update_status',  [ $this, 'ajax_update_status' ] );
        add_action( 'wp_ajax_hb_admin_delete',         [ $this, 'ajax_delete_reservation' ] );
        add_action( 'wp_ajax_hb_admin_save_reservation', [ $this, 'ajax_save_reservation' ] );
        add_action( 'wp_ajax_hb_admin_get_availability', [ $this, 'ajax_get_availability' ] );
    }

    public function register_menus() {
        add_menu_page(
            __( 'Hotel Booking', 'hotel-booking' ),
            __( 'Hotel Booking', 'hotel-booking' ),
            'manage_options',
            'hotel-booking',
            [ $this, 'page_dashboard' ],
            'dashicons-calendar-alt',
            30
        );

        add_submenu_page(
            'hotel-booking',
            __( 'Dashboard', 'hotel-booking' ),
            __( 'Dashboard', 'hotel-booking' ),
            'manage_options',
            'hotel-booking',
            [ $this, 'page_dashboard' ]
        );

        add_submenu_page(
            'hotel-booking',
            __( 'Reservations', 'hotel-booking' ),
            __( 'Reservations', 'hotel-booking' ),
            'manage_options',
            'hotel-booking-reservations',
            [ $this, 'page_reservations' ]
        );

        add_submenu_page(
            'hotel-booking',
            __( 'Add Reservation', 'hotel-booking' ),
            __( 'Add Reservation', 'hotel-booking' ),
            'manage_options',
            'hotel-booking-add',
            [ $this, 'page_reservation_edit' ]
        );

        add_submenu_page(
            'hotel-booking',
            __( 'Availability', 'hotel-booking' ),
            __( 'Availability', 'hotel-booking' ),
            'manage_options',
            'hotel-booking-availability',
            [ $this, 'page_availability' ]
        );

        add_submenu_page(
            'hotel-booking',
            __( 'Settings', 'hotel-booking' ),
            __( 'Settings', 'hotel-booking' ),
            'manage_options',
            'hotel-booking-settings',
            [ $this, 'page_settings' ]
        );
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'hotel-booking' ) === false && strpos( $hook, 'hb_room' ) === false ) {
            return;
        }
        wp_enqueue_style( 'hb-admin', HB_PLUGIN_URL . 'admin/assets/css/admin.css', [], HB_VERSION );
        wp_enqueue_script( 'hb-admin', HB_PLUGIN_URL . 'admin/assets/js/admin.js', [ 'jquery' ], HB_VERSION, true );
        wp_localize_script( 'hb-admin', 'hbAdmin', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'hb_admin_nonce' ),
            'i18n'    => [
                'confirm_delete'  => __( 'Delete this reservation? This cannot be undone.', 'hotel-booking' ),
                'confirm_cancel'  => __( 'Cancel this reservation?', 'hotel-booking' ),
                'saving'          => __( 'Saving...', 'hotel-booking' ),
                'success'         => __( 'Saved successfully.', 'hotel-booking' ),
            ],
        ] );
    }

    public function page_dashboard() {
        include HB_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    public function page_reservations() {
        $action = sanitize_text_field( $_GET['action'] ?? '' );
        if ( $action === 'edit' ) {
            include HB_PLUGIN_DIR . 'admin/views/reservation-edit.php';
        } else {
            include HB_PLUGIN_DIR . 'admin/views/reservations.php';
        }
    }

    public function page_reservation_edit() {
        include HB_PLUGIN_DIR . 'admin/views/reservation-edit.php';
    }

    public function page_availability() {
        include HB_PLUGIN_DIR . 'admin/views/availability.php';
    }

    public function page_settings() {
        // Handle save
        if ( isset( $_POST['hb_settings_nonce'] ) && wp_verify_nonce( $_POST['hb_settings_nonce'], 'hb_save_settings' ) ) {
            $settings = [
                'from_name'      => sanitize_text_field( $_POST['from_name'] ?? 'Rhein-Hotel Krone' ),
                'from_email'     => sanitize_email( $_POST['from_email'] ?? '' ),
                'admin_email'    => sanitize_email( $_POST['admin_email'] ?? '' ),
                'checkin_time'   => sanitize_text_field( $_POST['checkin_time'] ?? '15:00' ),
                'checkout_time'  => sanitize_text_field( $_POST['checkout_time'] ?? '11:00' ),
                'currency'       => sanitize_text_field( $_POST['currency'] ?? 'EUR' ),
                'currency_symbol'=> sanitize_text_field( $_POST['currency_symbol'] ?? '€' ),
                'deposit_percent'=> absint( $_POST['deposit_percent'] ?? 0 ),
            ];
            update_option( 'hotel_booking_settings', $settings );
            add_settings_error( 'hb_settings', 'saved', __( 'Settings saved.', 'hotel-booking' ), 'updated' );
        }
        include HB_PLUGIN_DIR . 'admin/views/settings.php';
    }

    /** AJAX: Update reservation status */
    public function ajax_update_status() {
        check_ajax_referer( 'hb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( -1 );

        $id     = absint( $_POST['id'] );
        $status = sanitize_text_field( $_POST['status'] );
        $result = Hotel_Booking_Reservation::update_status( $id, $status );

        if ( $result !== false ) {
            // Send cancellation email if cancelled
            if ( $status === 'cancelled' ) {
                $reservation = Hotel_Booking_DB::get_reservation( $id );
                if ( $reservation ) {
                    Hotel_Booking_Email::send_cancellation_email( $reservation );
                }
            }
            wp_send_json_success( [ 'message' => __( 'Status updated.', 'hotel-booking' ) ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Failed to update status.', 'hotel-booking' ) ] );
        }
    }

    /** AJAX: Delete reservation */
    public function ajax_delete_reservation() {
        check_ajax_referer( 'hb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( -1 );

        $id     = absint( $_POST['id'] );
        $result = Hotel_Booking_Reservation::delete( $id );

        $result !== false
            ? wp_send_json_success()
            : wp_send_json_error( [ 'message' => __( 'Failed to delete.', 'hotel-booking' ) ] );
    }

    /** AJAX: Save reservation (add or edit) */
    public function ajax_save_reservation() {
        check_ajax_referer( 'hb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( -1 );

        $id = absint( $_POST['id'] ?? 0 );

        $data = [
            'room_id'     => absint( $_POST['room_id'] ),
            'guest_name'  => sanitize_text_field( $_POST['guest_name'] ),
            'guest_email' => sanitize_email( $_POST['guest_email'] ),
            'guest_phone' => sanitize_text_field( $_POST['guest_phone'] ?? '' ),
            'check_in'    => sanitize_text_field( $_POST['check_in'] ),
            'check_out'   => sanitize_text_field( $_POST['check_out'] ),
            'adults'      => absint( $_POST['adults'] ?? 1 ),
            'children'    => absint( $_POST['children'] ?? 0 ),
            'total_price' => floatval( $_POST['total_price'] ?? 0 ),
            'status'      => sanitize_text_field( $_POST['status'] ?? 'confirmed' ),
            'notes'       => sanitize_textarea_field( $_POST['notes'] ?? '' ),
            'lang'        => in_array( $_POST['lang'] ?? 'en', [ 'en', 'de' ], true ) ? $_POST['lang'] : 'en',
        ];

        if ( $id ) {
            $result = Hotel_Booking_Reservation::update( $id, $data );
            $result !== false
                ? wp_send_json_success( [ 'message' => __( 'Reservation updated.', 'hotel-booking' ) ] )
                : wp_send_json_error( [ 'message' => __( 'Update failed.', 'hotel-booking' ) ] );
        } else {
            $data['reference'] = Hotel_Booking_Reservation::generate_reference();
            $result = Hotel_Booking_Reservation::create( $data );
            if ( is_wp_error( $result ) ) {
                wp_send_json_error( [ 'message' => $result->get_error_message() ] );
            }
            wp_send_json_success( [ 'message' => __( 'Reservation created.', 'hotel-booking' ), 'id' => $result ] );
        }
    }

    /** AJAX: Get room availability for calendar view */
    public function ajax_get_availability() {
        check_ajax_referer( 'hb_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( -1 );

        global $wpdb;

        $month  = absint( $_POST['month'] ?? date( 'n' ) );
        $year   = absint( $_POST['year']  ?? date( 'Y' ) );
        $room_id= absint( $_POST['room_id'] ?? 0 );

        $date_from = sprintf( '%04d-%02d-01', $year, $month );
        $date_to   = date( 'Y-m-t', strtotime( $date_from ) );

        $where = "check_in <= '$date_to' AND check_out >= '$date_from' AND status != 'cancelled'";
        if ( $room_id ) {
            $where .= $wpdb->prepare( ' AND room_id = %d', $room_id );
        }

        $reservations = $wpdb->get_results(
            "SELECT id, room_id, reference, guest_name, check_in, check_out, status
             FROM {$wpdb->prefix}hb_reservations
             WHERE $where ORDER BY check_in"
        );

        wp_send_json_success( [
            'reservations' => $reservations,
            'month'        => $month,
            'year'         => $year,
        ] );
    }
}
