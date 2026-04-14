<?php
defined( 'ABSPATH' ) || exit;

$ref         = sanitize_text_field( $_GET['booking_ref'] ?? '' );
$reservation = $ref ? Hotel_Booking_DB::get_reservation_by_reference( $ref ) : null;

if ( ! $reservation ) :
?>
    <div class="hb-conf-wrap hb-conf-not-found">
        <p><?php esc_html_e( 'Booking reference not found. Please check your confirmation email.', 'hotel-booking' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '← Back to Home', 'hotel-booking' ); ?></a>
    </div>
<?php
    return;
endif;

$nights    = (int) ( ( strtotime( $reservation->check_out ) - strtotime( $reservation->check_in ) ) / DAY_IN_SECONDS );
$room_name = get_the_title( $reservation->room_id ) ?: __( 'Hotel Room', 'hotel-booking' );
$settings  = get_option( 'hotel_booking_settings', [] );
$checkin_t = $settings['checkin_time']  ?? '15:00';
$checkout_t= $settings['checkout_time'] ?? '11:00';
?>
<div class="hb-conf-wrap">
    <div class="hb-conf-header">
        <div class="hb-conf-check">✓</div>
        <h1><?php esc_html_e( 'Booking Confirmed!', 'hotel-booking' ); ?></h1>
        <p><?php esc_html_e( 'Thank you for choosing Rhein-Hotel Krone. We look forward to welcoming you!', 'hotel-booking' ); ?></p>
    </div>

    <div class="hb-conf-ref">
        <span><?php esc_html_e( 'Booking Reference', 'hotel-booking' ); ?></span>
        <strong><?php echo esc_html( $reservation->reference ); ?></strong>
    </div>

    <div class="hb-conf-details">
        <div class="hb-conf-row">
            <span><?php esc_html_e( 'Room', 'hotel-booking' ); ?></span>
            <strong><?php echo esc_html( $room_name ); ?></strong>
        </div>
        <div class="hb-conf-row">
            <span><?php esc_html_e( 'Guest', 'hotel-booking' ); ?></span>
            <strong><?php echo esc_html( $reservation->guest_name ); ?></strong>
        </div>
        <div class="hb-conf-row">
            <span><?php esc_html_e( 'Check-in', 'hotel-booking' ); ?></span>
            <strong><?php echo esc_html( date_i18n( 'D, d M Y', strtotime( $reservation->check_in ) ) ); ?></strong>
            <small>(<?php esc_html_e( 'From', 'hotel-booking' ); ?> <?php echo esc_html( $checkin_t ); ?>)</small>
        </div>
        <div class="hb-conf-row">
            <span><?php esc_html_e( 'Check-out', 'hotel-booking' ); ?></span>
            <strong><?php echo esc_html( date_i18n( 'D, d M Y', strtotime( $reservation->check_out ) ) ); ?></strong>
            <small>(<?php esc_html_e( 'Until', 'hotel-booking' ); ?> <?php echo esc_html( $checkout_t ); ?>)</small>
        </div>
        <div class="hb-conf-row">
            <span><?php esc_html_e( 'Duration', 'hotel-booking' ); ?></span>
            <strong><?php echo esc_html( $nights ); ?> <?php esc_html_e( 'nights', 'hotel-booking' ); ?></strong>
        </div>
        <div class="hb-conf-row">
            <span><?php esc_html_e( 'Guests', 'hotel-booking' ); ?></span>
            <strong><?php echo esc_html( $reservation->adults ); ?> <?php esc_html_e( 'adults', 'hotel-booking' ); ?>
                <?php if ( $reservation->children ) echo ' + ' . esc_html( $reservation->children ) . ' ' . esc_html__( 'children', 'hotel-booking' ); ?>
            </strong>
        </div>
        <div class="hb-conf-row hb-conf-total">
            <span><?php esc_html_e( 'Total', 'hotel-booking' ); ?></span>
            <strong>€<?php echo number_format( $reservation->total_price, 2 ); ?></strong>
        </div>
    </div>

    <div class="hb-conf-contact">
        <h3><?php esc_html_e( 'Contact Us', 'hotel-booking' ); ?></h3>
        <p>
            <strong>Rhein-Hotel Krone</strong><br>
            Rheinuferstraße 8, 56154 Boppard am Rhein<br>
            📞 +49 6742 2313<br>
            ✉️ <a href="mailto:info@rhein-hotel-krone.de">info@rhein-hotel-krone.de</a>
        </p>
    </div>

    <div class="hb-conf-actions">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hb-conf-btn-home"><?php esc_html_e( '← Back to Home', 'hotel-booking' ); ?></a>
        <a href="javascript:window.print()" class="hb-conf-btn-print">🖨️ <?php esc_html_e( 'Print Confirmation', 'hotel-booking' ); ?></a>
    </div>
</div>
