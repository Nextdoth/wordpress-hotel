<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap hb-admin-wrap">
    <div class="hb-admin-header">
        <h1 class="hb-admin-title"><?php esc_html_e( 'Reservations', 'hotel-booking' ); ?></h1>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-add' ) ); ?>" class="hb-btn-primary">
            + <?php esc_html_e( 'Add Reservation', 'hotel-booking' ); ?>
        </a>
    </div>

    <?php
    // Filters
    $status   = sanitize_text_field( $_GET['status'] ?? '' );
    $room_id  = absint( $_GET['room_id'] ?? 0 );
    $search   = sanitize_text_field( $_GET['search'] ?? '' );
    $paged    = max( 1, absint( $_GET['paged'] ?? 1 ) );
    $per_page = 20;
    $offset   = ( $paged - 1 ) * $per_page;

    $reservations = Hotel_Booking_DB::get_reservations( [
        'status'   => $status,
        'room_id'  => $room_id,
        'search'   => $search,
        'limit'    => $per_page,
        'offset'   => $offset,
    ] );

    $total = Hotel_Booking_DB::count_reservations( [
        'status'  => $status,
        'room_id' => $room_id,
    ] );
    $pages = ceil( $total / $per_page );

    // Get all rooms for filter
    $rooms_for_filter = get_posts( [ 'post_type' => 'hb_room', 'posts_per_page' => -1, 'post_status' => 'publish' ] );
    ?>

    <!-- Filters -->
    <div class="hb-filters">
        <form method="get" action="">
            <input type="hidden" name="page" value="hotel-booking-reservations">
            <div class="hb-filter-row">
                <input type="text" name="search" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by name, email, reference...', 'hotel-booking' ); ?>" class="hb-search-input">
                <select name="status">
                    <option value=""><?php esc_html_e( 'All Statuses', 'hotel-booking' ); ?></option>
                    <?php foreach ( [ 'confirmed', 'pending', 'cancelled', 'completed' ] as $s ) : ?>
                    <option value="<?php echo esc_attr( $s ); ?>" <?php selected( $status, $s ); ?>><?php echo esc_html( ucfirst( $s ) ); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="room_id">
                    <option value="0"><?php esc_html_e( 'All Rooms', 'hotel-booking' ); ?></option>
                    <?php foreach ( $rooms_for_filter as $room ) : ?>
                    <option value="<?php echo esc_attr( $room->ID ); ?>" <?php selected( $room_id, $room->ID ); ?>><?php echo esc_html( $room->post_title ); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="hb-btn-secondary"><?php esc_html_e( 'Filter', 'hotel-booking' ); ?></button>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-reservations' ) ); ?>" class="hb-btn-link"><?php esc_html_e( 'Clear', 'hotel-booking' ); ?></a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="hb-card">
        <table class="hb-table hb-table-full">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Reference', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Guest', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Room', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Check-in', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Check-out', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Guests', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Total', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'hotel-booking' ); ?></th>
                    <th><?php esc_html_e( 'Actions', 'hotel-booking' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php if ( empty( $reservations ) ) : ?>
                <tr><td colspan="9" class="hb-empty"><?php esc_html_e( 'No reservations found.', 'hotel-booking' ); ?></td></tr>
            <?php else : ?>
                <?php foreach ( $reservations as $r ) :
                    $nights = (int) ( ( strtotime( $r->check_out ) - strtotime( $r->check_in ) ) / DAY_IN_SECONDS );
                ?>
                <tr data-id="<?php echo esc_attr( $r->id ); ?>">
                    <td><strong><?php echo esc_html( $r->reference ); ?></strong></td>
                    <td>
                        <strong><?php echo esc_html( $r->guest_name ); ?></strong><br>
                        <small><?php echo esc_html( $r->guest_email ); ?></small>
                    </td>
                    <td><?php echo esc_html( get_the_title( $r->room_id ) ?: '—' ); ?></td>
                    <td><?php echo esc_html( $r->check_in ); ?></td>
                    <td><?php echo esc_html( $r->check_out ); ?><br><small><?php echo esc_html( $nights ); ?> <?php esc_html_e( 'nights', 'hotel-booking' ); ?></small></td>
                    <td><?php echo esc_html( $r->adults ); ?>+<?php echo esc_html( $r->children ); ?></td>
                    <td><strong>€<?php echo number_format( $r->total_price, 2 ); ?></strong></td>
                    <td>
                        <select class="hb-status-select" data-id="<?php echo esc_attr( $r->id ); ?>">
                            <?php foreach ( [ 'confirmed', 'pending', 'cancelled', 'completed' ] as $s ) : ?>
                            <option value="<?php echo esc_attr( $s ); ?>" <?php selected( $r->status, $s ); ?>><?php echo esc_html( ucfirst( $s ) ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="hb-row-actions">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-reservations&action=edit&id=' . $r->id ) ); ?>" class="hb-btn-icon" title="<?php esc_attr_e( 'Edit', 'hotel-booking' ); ?>">✏️</a>
                        <button class="hb-btn-icon hb-btn-delete" data-id="<?php echo esc_attr( $r->id ); ?>" title="<?php esc_attr_e( 'Delete', 'hotel-booking' ); ?>">🗑️</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ( $pages > 1 ) : ?>
    <div class="hb-pagination">
        <?php for ( $i = 1; $i <= $pages; $i++ ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'paged', $i ) ); ?>" class="hb-page-btn <?php echo $i === $paged ? 'hb-page-current' : ''; ?>"><?php echo esc_html( $i ); ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
