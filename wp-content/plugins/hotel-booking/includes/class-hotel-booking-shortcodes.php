<?php
defined( 'ABSPATH' ) || exit;

/**
 * Registers all frontend shortcodes.
 */
class Hotel_Booking_Shortcodes {

    public static function register() {
        add_shortcode( 'hotel_booking',           [ __CLASS__, 'booking_form' ] );
        add_shortcode( 'hotel_room_availability', [ __CLASS__, 'availability_checker' ] );
        add_shortcode( 'hotel_booking_confirmation', [ __CLASS__, 'booking_confirmation' ] );
    }

    /**
     * [hotel_booking] — Full multi-step booking form.
     */
    public static function booking_form( $atts ) {
        $atts = shortcode_atts( [
            'room_id' => 0,
            'class'   => '',
        ], $atts );

        ob_start();
        include HB_PLUGIN_DIR . 'public/views/booking-form.php';
        return ob_get_clean();
    }

    /**
     * [hotel_room_availability room_id="123"] — Availability mini-checker.
     */
    public static function availability_checker( $atts ) {
        $atts = shortcode_atts( [ 'room_id' => 0 ], $atts );

        ob_start();
        ?>
        <div class="hb-availability-checker" data-room="<?php echo absint( $atts['room_id'] ); ?>">
            <form class="hb-avail-form">
                <?php wp_nonce_field( 'hb_nonce', 'hb_nonce' ); ?>
                <input type="hidden" name="action" value="hb_check_availability">
                <?php if ( $atts['room_id'] ) : ?>
                <input type="hidden" name="room_id" value="<?php echo absint( $atts['room_id'] ); ?>">
                <?php endif; ?>
                <div class="hb-avail-row">
                    <div class="hb-avail-field">
                        <label><?php esc_html_e( 'Check-in', 'hotel-booking' ); ?></label>
                        <input type="date" name="check_in" required min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                    </div>
                    <div class="hb-avail-field">
                        <label><?php esc_html_e( 'Check-out', 'hotel-booking' ); ?></label>
                        <input type="date" name="check_out" required>
                    </div>
                    <button type="submit" class="hb-btn hb-btn-primary">
                        <?php esc_html_e( 'Check Availability', 'hotel-booking' ); ?>
                    </button>
                </div>
            </form>
            <div class="hb-avail-result"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * [hotel_booking_confirmation] — Shows confirmation page content.
     */
    public static function booking_confirmation( $atts ) {
        ob_start();
        include HB_PLUGIN_DIR . 'public/views/booking-confirmation.php';
        return ob_get_clean();
    }
}
