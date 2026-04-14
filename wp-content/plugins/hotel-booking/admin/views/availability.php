<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap hb-admin-wrap">
    <div class="hb-admin-header">
        <h1 class="hb-admin-title"><?php esc_html_e( 'Availability Calendar', 'hotel-booking' ); ?></h1>
    </div>

    <?php $rooms = get_posts( [ 'post_type' => 'hb_room', 'posts_per_page' => -1, 'post_status' => 'publish' ] ); ?>

    <div class="hb-card">
        <div class="hb-calendar-controls">
            <button id="hb-cal-prev" class="hb-btn-secondary">← <?php esc_html_e( 'Previous', 'hotel-booking' ); ?></button>
            <h2 id="hb-cal-title"></h2>
            <button id="hb-cal-next" class="hb-btn-secondary"><?php esc_html_e( 'Next', 'hotel-booking' ); ?> →</button>
            <select id="hb-cal-room-filter">
                <option value="0"><?php esc_html_e( 'All Rooms', 'hotel-booking' ); ?></option>
                <?php foreach ( $rooms as $room ) : ?>
                <option value="<?php echo esc_attr( $room->ID ); ?>"><?php echo esc_html( $room->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="hb-calendar-wrap">
            <!-- Calendar rendered by JS -->
            <div class="hb-calendar-loading"><?php esc_html_e( 'Loading calendar...', 'hotel-booking' ); ?></div>
        </div>

        <div class="hb-calendar-legend">
            <span class="hb-legend-item"><span class="hb-legend-dot hb-legend-confirmed"></span><?php esc_html_e( 'Confirmed', 'hotel-booking' ); ?></span>
            <span class="hb-legend-item"><span class="hb-legend-dot hb-legend-pending"></span><?php esc_html_e( 'Pending', 'hotel-booking' ); ?></span>
            <span class="hb-legend-item"><span class="hb-legend-dot hb-legend-free"></span><?php esc_html_e( 'Available', 'hotel-booking' ); ?></span>
        </div>
    </div>
</div>
