<?php
defined( 'ABSPATH' ) || exit;

$id          = absint( $_GET['id'] ?? 0 );
$reservation = $id ? Hotel_Booking_DB::get_reservation( $id ) : null;
$rooms       = get_posts( [ 'post_type' => 'hb_room', 'posts_per_page' => -1, 'post_status' => 'publish' ] );
$is_edit     = (bool) $reservation;
$title       = $is_edit ? __( 'Edit Reservation', 'hotel-booking' ) : __( 'Add Reservation', 'hotel-booking' );
?>
<div class="wrap hb-admin-wrap">
    <div class="hb-admin-header">
        <h1 class="hb-admin-title"><?php echo esc_html( $title ); ?></h1>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-reservations' ) ); ?>" class="hb-btn-secondary">← <?php esc_html_e( 'Back to Reservations', 'hotel-booking' ); ?></a>
    </div>

    <div class="hb-edit-grid">
        <div class="hb-card hb-edit-main">
            <form id="hb-reservation-form">
                <input type="hidden" name="action" value="hb_admin_save_reservation">
                <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'hb_admin_nonce' ) ); ?>">
                <input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>">

                <div class="hb-form-grid">
                    <div class="hb-form-section">
                        <h3><?php esc_html_e( 'Guest Information', 'hotel-booking' ); ?></h3>
                        <div class="hb-form-row">
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Guest Name *', 'hotel-booking' ); ?></label>
                                <input type="text" name="guest_name" value="<?php echo esc_attr( $reservation->guest_name ?? '' ); ?>" required>
                            </div>
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Email *', 'hotel-booking' ); ?></label>
                                <input type="email" name="guest_email" value="<?php echo esc_attr( $reservation->guest_email ?? '' ); ?>" required>
                            </div>
                        </div>
                        <div class="hb-form-row">
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Phone', 'hotel-booking' ); ?></label>
                                <input type="tel" name="guest_phone" value="<?php echo esc_attr( $reservation->guest_phone ?? '' ); ?>">
                            </div>
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Language', 'hotel-booking' ); ?></label>
                                <select name="lang">
                                    <option value="en" <?php selected( $reservation->lang ?? 'en', 'en' ); ?>>English</option>
                                    <option value="de" <?php selected( $reservation->lang ?? 'en', 'de' ); ?>>Deutsch</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="hb-form-section">
                        <h3><?php esc_html_e( 'Booking Details', 'hotel-booking' ); ?></h3>
                        <div class="hb-form-row">
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Room *', 'hotel-booking' ); ?></label>
                                <select name="room_id" id="hb-room-select" required>
                                    <option value=""><?php esc_html_e( '— Select Room —', 'hotel-booking' ); ?></option>
                                    <?php foreach ( $rooms as $room ) : ?>
                                    <option value="<?php echo esc_attr( $room->ID ); ?>"
                                        data-price="<?php echo esc_attr( get_post_meta( $room->ID, '_hb_price_per_night', true ) ); ?>"
                                        <?php selected( ( $reservation->room_id ?? 0 ), $room->ID ); ?>>
                                        <?php echo esc_html( $room->post_title ); ?>
                                        (€<?php echo number_format( (float) get_post_meta( $room->ID, '_hb_price_per_night', true ), 2 ); ?>/<?php esc_html_e( 'night', 'hotel-booking' ); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Status', 'hotel-booking' ); ?></label>
                                <select name="status">
                                    <?php foreach ( [ 'confirmed', 'pending', 'cancelled', 'completed' ] as $s ) : ?>
                                    <option value="<?php echo esc_attr( $s ); ?>" <?php selected( $reservation->status ?? 'confirmed', $s ); ?>><?php echo esc_html( ucfirst( $s ) ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="hb-form-row">
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Check-in *', 'hotel-booking' ); ?></label>
                                <input type="date" name="check_in" id="hb-check-in" value="<?php echo esc_attr( $reservation->check_in ?? '' ); ?>" required>
                            </div>
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Check-out *', 'hotel-booking' ); ?></label>
                                <input type="date" name="check_out" id="hb-check-out" value="<?php echo esc_attr( $reservation->check_out ?? '' ); ?>" required>
                            </div>
                        </div>
                        <div class="hb-form-row">
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Adults', 'hotel-booking' ); ?></label>
                                <input type="number" name="adults" value="<?php echo esc_attr( $reservation->adults ?? 1 ); ?>" min="1" max="10">
                            </div>
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Children', 'hotel-booking' ); ?></label>
                                <input type="number" name="children" value="<?php echo esc_attr( $reservation->children ?? 0 ); ?>" min="0" max="5">
                            </div>
                        </div>
                    </div>

                    <div class="hb-form-section hb-form-full">
                        <h3><?php esc_html_e( 'Pricing & Notes', 'hotel-booking' ); ?></h3>
                        <div class="hb-form-row">
                            <div class="hb-form-field">
                                <label><?php esc_html_e( 'Total Price (€)', 'hotel-booking' ); ?></label>
                                <input type="number" name="total_price" id="hb-total-price" value="<?php echo esc_attr( $reservation->total_price ?? '' ); ?>" step="0.01" min="0">
                                <small><?php esc_html_e( 'Auto-calculated based on room rate × nights.', 'hotel-booking' ); ?></small>
                            </div>
                        </div>
                        <div class="hb-form-field">
                            <label><?php esc_html_e( 'Notes', 'hotel-booking' ); ?></label>
                            <textarea name="notes" rows="4"><?php echo esc_textarea( $reservation->notes ?? '' ); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="hb-form-actions">
                    <button type="submit" class="hb-btn-primary" id="hb-save-btn">
                        <?php echo $is_edit ? esc_html__( 'Update Reservation', 'hotel-booking' ) : esc_html__( 'Create Reservation', 'hotel-booking' ); ?>
                    </button>
                    <span class="hb-save-feedback"></span>
                </div>
            </form>
        </div>

        <?php if ( $reservation ) : ?>
        <div class="hb-edit-sidebar">
            <div class="hb-card">
                <h3><?php esc_html_e( 'Booking Summary', 'hotel-booking' ); ?></h3>
                <div class="hb-summary-ref"><?php echo esc_html( $reservation->reference ); ?></div>
                <ul class="hb-summary-list">
                    <li><span><?php esc_html_e( 'Created', 'hotel-booking' ); ?></span><strong><?php echo esc_html( $reservation->created_at ); ?></strong></li>
                    <li><span><?php esc_html_e( 'Nights', 'hotel-booking' ); ?></span><strong><?php
                        $nights = (int) ( ( strtotime( $reservation->check_out ) - strtotime( $reservation->check_in ) ) / DAY_IN_SECONDS );
                        echo esc_html( $nights );
                    ?></strong></li>
                    <li><span><?php esc_html_e( 'Total', 'hotel-booking' ); ?></span><strong>€<?php echo number_format( $reservation->total_price, 2 ); ?></strong></li>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
