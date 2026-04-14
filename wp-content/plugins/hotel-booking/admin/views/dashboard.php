<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap hb-admin-wrap">
    <div class="hb-admin-header">
        <h1 class="hb-admin-title"><?php esc_html_e( 'Hotel Booking Dashboard', 'hotel-booking' ); ?></h1>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-add' ) ); ?>" class="hb-btn-primary">
            + <?php esc_html_e( 'New Reservation', 'hotel-booking' ); ?>
        </a>
    </div>

    <?php
    $today         = current_time( 'Y-m-d' );
    $month         = (int) current_time( 'm' );
    $year          = (int) current_time( 'Y' );
    $last_month    = $month === 1 ? 12 : $month - 1;
    $last_year     = $month === 1 ? $year - 1 : $year;

    $total_confirmed  = Hotel_Booking_DB::count_reservations( [ 'status' => 'confirmed' ] );
    $total_pending    = Hotel_Booking_DB::count_reservations( [ 'status' => 'pending' ] );
    $total_cancelled  = Hotel_Booking_DB::count_reservations( [ 'status' => 'cancelled' ] );
    $revenue_month    = Hotel_Booking_DB::get_total_revenue( $month, $year );
    $revenue_last     = Hotel_Booking_DB::get_total_revenue( $last_month, $last_year );
    $revenue_change   = $revenue_last > 0 ? round( ( ( $revenue_month - $revenue_last ) / $revenue_last ) * 100 ) : 0;
    $occupancy        = Hotel_Booking_Reservation::get_occupancy_rate( $month, $year );
    $checkins_today   = Hotel_Booking_Reservation::get_todays_checkins();
    $checkouts_today  = Hotel_Booking_Reservation::get_todays_checkouts();
    $recent           = Hotel_Booking_DB::get_reservations( [ 'limit' => 5, 'orderby' => 'created_at', 'order' => 'DESC' ] );
    ?>

    <!-- Stats Grid -->
    <div class="hb-stats-grid">
        <div class="hb-stat-card hb-stat-confirmed">
            <div class="hb-stat-icon">📅</div>
            <div class="hb-stat-value"><?php echo esc_html( $total_confirmed ); ?></div>
            <div class="hb-stat-label"><?php esc_html_e( 'Confirmed Bookings', 'hotel-booking' ); ?></div>
        </div>
        <div class="hb-stat-card hb-stat-revenue">
            <div class="hb-stat-icon">💶</div>
            <div class="hb-stat-value">€<?php echo number_format( $revenue_month, 0, '.', ',' ); ?></div>
            <div class="hb-stat-label">
                <?php esc_html_e( 'Revenue This Month', 'hotel-booking' ); ?>
                <?php if ( $revenue_change != 0 ) : ?>
                <span class="hb-trend <?php echo $revenue_change > 0 ? 'hb-trend-up' : 'hb-trend-down'; ?>">
                    <?php echo ( $revenue_change > 0 ? '▲' : '▼' ) . abs( $revenue_change ) . '%'; ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="hb-stat-card hb-stat-occupancy">
            <div class="hb-stat-icon">🏨</div>
            <div class="hb-stat-value"><?php echo esc_html( $occupancy ); ?>%</div>
            <div class="hb-stat-label"><?php esc_html_e( 'Occupancy Rate', 'hotel-booking' ); ?></div>
            <div class="hb-occupancy-bar"><div class="hb-occupancy-fill" style="width:<?php echo esc_attr( $occupancy ); ?>%"></div></div>
        </div>
        <div class="hb-stat-card hb-stat-today">
            <div class="hb-stat-icon">🔑</div>
            <div class="hb-stat-value"><?php echo esc_html( count( $checkins_today ) ); ?></div>
            <div class="hb-stat-label"><?php esc_html_e( 'Check-ins Today', 'hotel-booking' ); ?></div>
        </div>
        <div class="hb-stat-card hb-stat-checkout">
            <div class="hb-stat-icon">🚪</div>
            <div class="hb-stat-value"><?php echo esc_html( count( $checkouts_today ) ); ?></div>
            <div class="hb-stat-label"><?php esc_html_e( 'Check-outs Today', 'hotel-booking' ); ?></div>
        </div>
        <div class="hb-stat-card hb-stat-pending">
            <div class="hb-stat-icon">⏳</div>
            <div class="hb-stat-value"><?php echo esc_html( $total_pending ); ?></div>
            <div class="hb-stat-label"><?php esc_html_e( 'Pending', 'hotel-booking' ); ?></div>
        </div>
    </div>

    <!-- Two columns -->
    <div class="hb-dashboard-cols">

        <!-- Recent Reservations -->
        <div class="hb-dashboard-col">
            <div class="hb-card">
                <div class="hb-card-header">
                    <h3><?php esc_html_e( 'Recent Reservations', 'hotel-booking' ); ?></h3>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-reservations' ) ); ?>"><?php esc_html_e( 'View All', 'hotel-booking' ); ?></a>
                </div>
                <table class="hb-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Reference', 'hotel-booking' ); ?></th>
                            <th><?php esc_html_e( 'Guest', 'hotel-booking' ); ?></th>
                            <th><?php esc_html_e( 'Check-in', 'hotel-booking' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'hotel-booking' ); ?></th>
                            <th><?php esc_html_e( 'Total', 'hotel-booking' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ( empty( $recent ) ) : ?>
                        <tr><td colspan="5" class="hb-empty"><?php esc_html_e( 'No reservations yet.', 'hotel-booking' ); ?></td></tr>
                    <?php else : ?>
                        <?php foreach ( $recent as $r ) : ?>
                        <tr>
                            <td><a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-reservations&action=edit&id=' . $r->id ) ); ?>"><?php echo esc_html( $r->reference ); ?></a></td>
                            <td><?php echo esc_html( $r->guest_name ); ?></td>
                            <td><?php echo esc_html( $r->check_in ); ?></td>
                            <td><span class="hb-status hb-status-<?php echo esc_attr( $r->status ); ?>"><?php echo esc_html( ucfirst( $r->status ) ); ?></span></td>
                            <td>€<?php echo number_format( $r->total_price, 2 ); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Today's Activity -->
        <div class="hb-dashboard-col">
            <?php if ( ! empty( $checkins_today ) ) : ?>
            <div class="hb-card">
                <div class="hb-card-header">
                    <h3>🔑 <?php esc_html_e( "Today's Check-ins", 'hotel-booking' ); ?></h3>
                </div>
                <ul class="hb-activity-list">
                    <?php foreach ( $checkins_today as $r ) : ?>
                    <li>
                        <strong><?php echo esc_html( $r->guest_name ); ?></strong>
                        <span><?php echo esc_html( get_the_title( $r->room_id ) ); ?></span>
                        <span class="hb-ref"><?php echo esc_html( $r->reference ); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if ( ! empty( $checkouts_today ) ) : ?>
            <div class="hb-card" style="margin-top:20px;">
                <div class="hb-card-header">
                    <h3>🚪 <?php esc_html_e( "Today's Check-outs", 'hotel-booking' ); ?></h3>
                </div>
                <ul class="hb-activity-list">
                    <?php foreach ( $checkouts_today as $r ) : ?>
                    <li>
                        <strong><?php echo esc_html( $r->guest_name ); ?></strong>
                        <span><?php echo esc_html( get_the_title( $r->room_id ) ); ?></span>
                        <span class="hb-ref"><?php echo esc_html( $r->reference ); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if ( empty( $checkins_today ) && empty( $checkouts_today ) ) : ?>
            <div class="hb-card">
                <div class="hb-empty-state">
                    <p>✅ <?php esc_html_e( 'No check-ins or check-outs today.', 'hotel-booking' ); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
