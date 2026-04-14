<?php
defined( 'ABSPATH' ) || exit;

/**
 * Handles database table creation and upgrades.
 */
class Hotel_Booking_DB {

    /**
     * Create or upgrade database tables on plugin activation.
     */
    public static function install() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Reservations table
        $sql_reservations = "CREATE TABLE {$wpdb->prefix}hb_reservations (
            id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            room_id       BIGINT UNSIGNED NOT NULL,
            guest_name    VARCHAR(100) NOT NULL,
            guest_email   VARCHAR(100) NOT NULL,
            guest_phone   VARCHAR(50) DEFAULT '',
            check_in      DATE NOT NULL,
            check_out     DATE NOT NULL,
            adults        TINYINT UNSIGNED NOT NULL DEFAULT 1,
            children      TINYINT UNSIGNED NOT NULL DEFAULT 0,
            total_price   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            status        VARCHAR(20) NOT NULL DEFAULT 'confirmed',
            reference     VARCHAR(20) NOT NULL,
            notes         TEXT DEFAULT '',
            lang          VARCHAR(5) DEFAULT 'en',
            created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY reference (reference),
            KEY room_id (room_id),
            KEY check_in (check_in),
            KEY check_out (check_out),
            KEY status (status)
        ) $charset_collate;";

        // Seasonal rates table
        $sql_rates = "CREATE TABLE {$wpdb->prefix}hb_rates (
            id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            room_id         BIGINT UNSIGNED NOT NULL,
            date_from       DATE NOT NULL,
            date_to         DATE NOT NULL,
            price_per_night DECIMAL(10,2) NOT NULL,
            PRIMARY KEY  (id),
            KEY room_id (room_id)
        ) $charset_collate;";

        // Blocked dates table
        $sql_blocked = "CREATE TABLE {$wpdb->prefix}hb_blocked_dates (
            id        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            room_id   BIGINT UNSIGNED NOT NULL,
            date_from DATE NOT NULL,
            date_to   DATE NOT NULL,
            reason    VARCHAR(255) DEFAULT '',
            PRIMARY KEY  (id),
            KEY room_id (room_id)
        ) $charset_collate;";

        dbDelta( $sql_reservations );
        dbDelta( $sql_rates );
        dbDelta( $sql_blocked );

        update_option( 'hb_db_version', HB_DB_VERSION );
    }

    /**
     * Called on plugin deactivation (tables preserved).
     */
    public static function deactivate() {
        // Intentionally empty — tables preserved for data safety
    }

    /**
     * Get all reservations with optional filters.
     *
     * @param array $args
     * @return array
     */
    public static function get_reservations( $args = [] ) {
        global $wpdb;

        $defaults = [
            'status'   => '',
            'room_id'  => 0,
            'date_from'=> '',
            'date_to'  => '',
            'search'   => '',
            'orderby'  => 'created_at',
            'order'    => 'DESC',
            'limit'    => 20,
            'offset'   => 0,
        ];
        $args = wp_parse_args( $args, $defaults );

        $where  = [];
        $params = [];

        if ( ! empty( $args['status'] ) ) {
            $where[]  = 'status = %s';
            $params[] = sanitize_text_field( $args['status'] );
        }
        if ( ! empty( $args['room_id'] ) ) {
            $where[]  = 'room_id = %d';
            $params[] = absint( $args['room_id'] );
        }
        if ( ! empty( $args['date_from'] ) ) {
            $where[]  = 'check_in >= %s';
            $params[] = sanitize_text_field( $args['date_from'] );
        }
        if ( ! empty( $args['date_to'] ) ) {
            $where[]  = 'check_out <= %s';
            $params[] = sanitize_text_field( $args['date_to'] );
        }
        if ( ! empty( $args['search'] ) ) {
            $where[]  = '(guest_name LIKE %s OR guest_email LIKE %s OR reference LIKE %s)';
            $like      = '%' . $wpdb->esc_like( sanitize_text_field( $args['search'] ) ) . '%';
            $params[]  = $like;
            $params[]  = $like;
            $params[]  = $like;
        }

        $where_sql = $where ? 'WHERE ' . implode( ' AND ', $where ) : '';

        $allowed_order = [ 'id', 'created_at', 'check_in', 'check_out', 'guest_name', 'status', 'total_price' ];
        $orderby       = in_array( $args['orderby'], $allowed_order, true ) ? $args['orderby'] : 'created_at';
        $order         = strtoupper( $args['order'] ) === 'ASC' ? 'ASC' : 'DESC';

        $limit  = absint( $args['limit'] );
        $offset = absint( $args['offset'] );

        if ( $params ) {
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}hb_reservations $where_sql ORDER BY $orderby $order LIMIT $limit OFFSET $offset",
                ...$params
            );
        } else {
            $query = "SELECT * FROM {$wpdb->prefix}hb_reservations $where_sql ORDER BY $orderby $order LIMIT $limit OFFSET $offset";
        }

        return $wpdb->get_results( $query );
    }

    /**
     * Count reservations with optional filters.
     */
    public static function count_reservations( $args = [] ) {
        global $wpdb;

        $defaults = [ 'status' => '', 'room_id' => 0 ];
        $args     = wp_parse_args( $args, $defaults );
        $where    = [];
        $params   = [];

        if ( ! empty( $args['status'] ) ) {
            $where[]  = 'status = %s';
            $params[] = $args['status'];
        }
        if ( ! empty( $args['room_id'] ) ) {
            $where[]  = 'room_id = %d';
            $params[] = $args['room_id'];
        }

        $where_sql = $where ? 'WHERE ' . implode( ' AND ', $where ) : '';

        if ( $params ) {
            return (int) $wpdb->get_var(
                $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}hb_reservations $where_sql", ...$params )
            );
        }
        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}hb_reservations $where_sql" );
    }

    /**
     * Get single reservation by ID.
     */
    public static function get_reservation( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hb_reservations WHERE id = %d",
            absint( $id )
        ) );
    }

    /**
     * Get reservation by reference number.
     */
    public static function get_reservation_by_reference( $ref ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hb_reservations WHERE reference = %s",
            sanitize_text_field( $ref )
        ) );
    }

    /**
     * Check room availability for date range.
     * Returns true if room is available.
     */
    public static function is_room_available( $room_id, $check_in, $check_out, $exclude_id = 0 ) {
        global $wpdb;

        $room_id   = absint( $room_id );
        $check_in  = sanitize_text_field( $check_in );
        $check_out = sanitize_text_field( $check_out );

        // How many physical units of this room type exist
        $quantity = (int) get_post_meta( $room_id, '_hb_room_quantity', true );
        if ( $quantity < 1 ) $quantity = 1;

        // Count active conflicting reservations
        $conflict_count = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}hb_reservations
             WHERE room_id = %d
             AND status NOT IN ('cancelled')
             AND id != %d
             AND check_in < %s
             AND check_out > %s",
            $room_id, absint( $exclude_id ), $check_out, $check_in
        ) );

        // Still available if booked count < total units
        if ( $conflict_count >= $quantity ) {
            return false;
        }

        // Check blocked dates
        $blocked = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}hb_blocked_dates
             WHERE room_id = %d
             AND date_from < %s
             AND date_to > %s",
            $room_id, $check_out, $check_in
        ) );

        return $blocked === '0';
    }

    /**
     * Get price per night for a room (checks seasonal rates first).
     */
    public static function get_room_price( $room_id, $check_in ) {
        global $wpdb;

        $seasonal = $wpdb->get_var( $wpdb->prepare(
            "SELECT price_per_night FROM {$wpdb->prefix}hb_rates
             WHERE room_id = %d AND date_from <= %s AND date_to >= %s
             ORDER BY date_from DESC LIMIT 1",
            absint( $room_id ), $check_in, $check_in
        ) );

        if ( $seasonal !== null ) {
            return (float) $seasonal;
        }

        return (float) get_post_meta( $room_id, '_hb_price_per_night', true );
    }

    /**
     * Get total revenue.
     */
    public static function get_total_revenue( $month = null, $year = null ) {
        global $wpdb;

        if ( $month && $year ) {
            return (float) $wpdb->get_var( $wpdb->prepare(
                "SELECT SUM(total_price) FROM {$wpdb->prefix}hb_reservations
                 WHERE status != 'cancelled'
                 AND MONTH(created_at) = %d AND YEAR(created_at) = %d",
                absint( $month ), absint( $year )
            ) );
        }

        return (float) $wpdb->get_var(
            "SELECT SUM(total_price) FROM {$wpdb->prefix}hb_reservations WHERE status != 'cancelled'"
        );
    }
}
