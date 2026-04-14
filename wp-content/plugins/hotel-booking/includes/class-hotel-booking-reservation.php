<?php
defined( 'ABSPATH' ) || exit;

/**
 * Handles reservation creation, retrieval, and availability logic.
 */
class Hotel_Booking_Reservation {

    /**
     * Generate a unique booking reference number.
     */
    public static function generate_reference() {
        global $wpdb;
        do {
            $ref = 'HK-' . strtoupper( substr( md5( uniqid( rand(), true ) ), 0, 8 ) );
            $exists = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}hb_reservations WHERE reference = %s", $ref
            ) );
        } while ( $exists > 0 );
        return $ref;
    }

    /**
     * Create a new reservation.
     *
     * @param array $data
     * @return int|WP_Error  Inserted row ID or WP_Error on failure.
     */
    public static function create( $data ) {
        global $wpdb;

        $required = [ 'room_id', 'guest_name', 'guest_email', 'check_in', 'check_out', 'reference' ];
        foreach ( $required as $field ) {
            if ( empty( $data[ $field ] ) ) {
                return new WP_Error( 'missing_field', sprintf( __( 'Missing required field: %s', 'hotel-booking' ), $field ) );
            }
        }

        $insert = [
            'room_id'     => absint( $data['room_id'] ),
            'guest_name'  => sanitize_text_field( $data['guest_name'] ),
            'guest_email' => sanitize_email( $data['guest_email'] ),
            'guest_phone' => sanitize_text_field( $data['guest_phone'] ?? '' ),
            'check_in'    => sanitize_text_field( $data['check_in'] ),
            'check_out'   => sanitize_text_field( $data['check_out'] ),
            'adults'      => absint( $data['adults'] ?? 1 ),
            'children'    => absint( $data['children'] ?? 0 ),
            'total_price' => floatval( $data['total_price'] ?? 0 ),
            'status'      => sanitize_text_field( $data['status'] ?? 'confirmed' ),
            'reference'   => sanitize_text_field( $data['reference'] ),
            'notes'       => sanitize_textarea_field( $data['notes'] ?? '' ),
            'lang'        => in_array( $data['lang'] ?? 'en', [ 'en', 'de' ], true ) ? $data['lang'] : 'en',
            'created_at'  => current_time( 'mysql' ),
        ];

        $result = $wpdb->insert( "{$wpdb->prefix}hb_reservations", $insert );

        if ( $result === false ) {
            return new WP_Error( 'db_error', __( 'Failed to save reservation. Please try again.', 'hotel-booking' ) );
        }

        return $wpdb->insert_id;
    }

    /**
     * Update a reservation.
     */
    public static function update( $id, $data ) {
        global $wpdb;

        $allowed = [
            'guest_name', 'guest_email', 'guest_phone', 'check_in', 'check_out',
            'adults', 'children', 'total_price', 'status', 'notes',
        ];

        $update = [];
        foreach ( $allowed as $field ) {
            if ( isset( $data[ $field ] ) ) {
                $update[ $field ] = $data[ $field ];
            }
        }

        if ( empty( $update ) ) {
            return false;
        }

        return $wpdb->update(
            "{$wpdb->prefix}hb_reservations",
            $update,
            [ 'id' => absint( $id ) ]
        );
    }

    /**
     * Delete a reservation.
     */
    public static function delete( $id ) {
        global $wpdb;
        return $wpdb->delete( "{$wpdb->prefix}hb_reservations", [ 'id' => absint( $id ) ] );
    }

    /**
     * Update reservation status.
     */
    public static function update_status( $id, $status ) {
        $allowed = [ 'pending', 'confirmed', 'cancelled', 'completed' ];
        if ( ! in_array( $status, $allowed, true ) ) {
            return false;
        }
        return self::update( $id, [ 'status' => $status ] );
    }

    /**
     * Get available rooms for a date range.
     *
     * @param string $check_in
     * @param string $check_out
     * @param int    $adults
     * @return array  Array of room data with pricing info.
     */
    public static function get_available_rooms( $check_in, $check_out, $adults = 1 ) {
        $args = [
            'post_type'      => 'hb_room',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => '_hb_capacity_adults',
                    'value'   => absint( $adults ),
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ],
            ],
        ];

        $rooms_query = new WP_Query( $args );
        $available   = [];
        $nights      = (int) ( ( strtotime( $check_out ) - strtotime( $check_in ) ) / DAY_IN_SECONDS );

        foreach ( $rooms_query->posts as $room ) {
            if ( Hotel_Booking_DB::is_room_available( $room->ID, $check_in, $check_out ) ) {
                $price_per_night = Hotel_Booking_DB::get_room_price( $room->ID, $check_in );
                $amenities_raw   = get_post_meta( $room->ID, '_hb_amenities', true );
                $amenities       = $amenities_raw ? array_map( 'trim', explode( ',', $amenities_raw ) ) : [];

                $available[] = [
                    'id'              => $room->ID,
                    'title'           => get_the_title( $room ),
                    'excerpt'         => get_the_excerpt( $room ),
                    'thumbnail'       => get_the_post_thumbnail_url( $room, 'large' ),
                    'price_per_night' => $price_per_night,
                    'total_price'     => $price_per_night * $nights,
                    'nights'          => $nights,
                    'adults'          => (int) get_post_meta( $room->ID, '_hb_capacity_adults', true ),
                    'children'        => (int) get_post_meta( $room->ID, '_hb_capacity_children', true ),
                    'size'            => get_post_meta( $room->ID, '_hb_room_size', true ),
                    'bed_type'        => get_post_meta( $room->ID, '_hb_bed_type', true ),
                    'view'            => get_post_meta( $room->ID, '_hb_view', true ),
                    'amenities'       => $amenities,
                    'url'             => get_permalink( $room ),
                ];
            }
        }

        wp_reset_postdata();
        return $available;
    }

    /**
     * Get today's check-ins.
     */
    public static function get_todays_checkins() {
        global $wpdb;
        $today = current_time( 'Y-m-d' );
        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hb_reservations WHERE check_in = %s AND status != 'cancelled'",
            $today
        ) );
    }

    /**
     * Get today's check-outs.
     */
    public static function get_todays_checkouts() {
        global $wpdb;
        $today = current_time( 'Y-m-d' );
        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hb_reservations WHERE check_out = %s AND status != 'cancelled'",
            $today
        ) );
    }

    /**
     * Get occupancy rate for a given month.
     * Returns percentage (0-100).
     */
    public static function get_occupancy_rate( $month = null, $year = null ) {
        global $wpdb;

        $month = $month ?: (int) current_time( 'm' );
        $year  = $year  ?: (int) current_time( 'Y' );

        $days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
        $total_rooms   = wp_count_posts( 'hb_room' )->publish;

        if ( ! $total_rooms ) return 0;

        $total_capacity = $total_rooms * $days_in_month;

        $booked_nights = $wpdb->get_var( $wpdb->prepare(
            "SELECT SUM(DATEDIFF(
                LEAST(check_out, %s),
                GREATEST(check_in, %s)
            ))
            FROM {$wpdb->prefix}hb_reservations
            WHERE status != 'cancelled'
            AND check_in < %s
            AND check_out > %s",
            "$year-$month-$days_in_month",
            "$year-$month-01",
            "$year-$month-$days_in_month",
            "$year-$month-01"
        ) );

        return $total_capacity > 0 ? round( ( $booked_nights / $total_capacity ) * 100 ) : 0;
    }
}
