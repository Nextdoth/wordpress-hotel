<?php
defined( 'ABSPATH' ) || exit;

/**
 * Handles all email notifications for the booking system.
 */
class Hotel_Booking_Email {

    /**
     * Send booking confirmation email to the guest.
     */
    public static function send_guest_confirmation( $reservation ) {
        if ( ! $reservation ) return false;

        $settings = get_option( 'hotel_booking_settings', [] );
        $lang     = $reservation->lang ?? 'en';
        $to       = $reservation->guest_email;
        $subject  = $lang === 'de'
            ? sprintf( 'Buchungsbestätigung – %s | Rhein-Hotel Krone', $reservation->reference )
            : sprintf( 'Booking Confirmation – %s | Rhein-Hotel Krone', $reservation->reference );

        $message = self::get_guest_email_html( $reservation, $lang );
        $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

        $from_name  = $settings['from_name']  ?? 'Rhein-Hotel Krone';
        $from_email = $settings['from_email'] ?? get_option( 'admin_email' );
        $headers[]  = "From: $from_name <$from_email>";

        return wp_mail( $to, $subject, $message, $headers );
    }

    /**
     * Send new booking notification to the hotel admin.
     */
    public static function send_admin_notification( $reservation ) {
        if ( ! $reservation ) return false;

        $settings   = get_option( 'hotel_booking_settings', [] );
        $admin_email = $settings['admin_email'] ?? get_option( 'admin_email' );
        $subject    = sprintf( '[New Booking] %s – %s to %s', $reservation->reference, $reservation->check_in, $reservation->check_out );
        $message    = self::get_admin_email_html( $reservation );
        $headers    = [
            'Content-Type: text/html; charset=UTF-8',
            'From: Rhein-Hotel Krone Website <' . get_option( 'admin_email' ) . '>',
        ];

        return wp_mail( $admin_email, $subject, $message, $headers );
    }

    /**
     * Send cancellation email to guest.
     */
    public static function send_cancellation_email( $reservation ) {
        if ( ! $reservation ) return false;

        $lang    = $reservation->lang ?? 'en';
        $to      = $reservation->guest_email;
        $subject = $lang === 'de'
            ? sprintf( 'Stornierung Ihrer Buchung %s | Rhein-Hotel Krone', $reservation->reference )
            : sprintf( 'Your booking %s has been cancelled | Rhein-Hotel Krone', $reservation->reference );

        ob_start();
        $colors = self::email_colors();
        ?>
        <!DOCTYPE html>
        <html>
        <body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding:40px 20px;">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;">
        <tr><td style="background:<?php echo $colors['navy']; ?>;padding:30px;text-align:center;">
            <h1 style="color:<?php echo $colors['gold']; ?>;margin:0;font-size:24px;">Rhein-Hotel Krone</h1>
        </td></tr>
        <tr><td style="padding:40px;">
            <h2 style="color:<?php echo $colors['navy']; ?>;">
                <?php echo $lang === 'de' ? 'Stornierungsbestätigung' : 'Cancellation Confirmation'; ?>
            </h2>
            <p><?php echo $lang === 'de' ? 'Ihre Buchung wurde storniert.' : 'Your booking has been cancelled.'; ?></p>
            <p><strong><?php echo $lang === 'de' ? 'Buchungsnummer:' : 'Reference:'; ?></strong> <?php echo esc_html( $reservation->reference ); ?></p>
        </td></tr>
        </table>
        </td></tr>
        </table>
        </body>
        </html>
        <?php
        $message = ob_get_clean();

        return wp_mail( $to, $subject, $message, [ 'Content-Type: text/html; charset=UTF-8' ] );
    }

    /**
     * Generate guest confirmation email HTML.
     */
    private static function get_guest_email_html( $reservation, $lang = 'en' ) {
        $room_title = get_the_title( $reservation->room_id ) ?: __( 'Hotel Room', 'hotel-booking' );
        $nights     = (int) ( ( strtotime( $reservation->check_out ) - strtotime( $reservation->check_in ) ) / DAY_IN_SECONDS );
        $colors     = self::email_colors();
        $settings   = get_option( 'hotel_booking_settings', [] );
        $checkin_time  = $settings['checkin_time']  ?? '15:00';
        $checkout_time = $settings['checkout_time'] ?? '11:00';

        $t = $lang === 'de' ? [
            'title'     => 'Buchungsbestätigung',
            'greeting'  => 'Vielen Dank für Ihre Buchung!',
            'sub'       => 'Wir freuen uns auf Ihren Besuch im Rhein-Hotel Krone.',
            'details'   => 'Buchungsdetails',
            'ref'       => 'Buchungsnummer',
            'room'      => 'Zimmer',
            'checkin'   => 'Check-in',
            'checkout'  => 'Check-out',
            'nights'    => 'Nächte',
            'guests'    => 'Gäste',
            'total'     => 'Gesamtbetrag',
            'checkin_t' => 'Check-in Zeit',
            'checkout_t'=> 'Check-out Zeit',
            'address'   => 'Unsere Adresse',
            'phone'     => 'Telefon',
            'footer'    => 'Rhein-Hotel Krone · Rheinuferstraße 8 · 56154 Boppard am Rhein',
        ] : [
            'title'     => 'Booking Confirmation',
            'greeting'  => 'Thank you for your booking!',
            'sub'       => 'We look forward to welcoming you at Rhein-Hotel Krone.',
            'details'   => 'Booking Details',
            'ref'       => 'Reference',
            'room'      => 'Room',
            'checkin'   => 'Check-in',
            'checkout'  => 'Check-out',
            'nights'    => 'Nights',
            'guests'    => 'Guests',
            'total'     => 'Total Amount',
            'checkin_t' => 'Check-in Time',
            'checkout_t'=> 'Check-out Time',
            'address'   => 'Our Address',
            'phone'     => 'Phone',
            'footer'    => 'Rhein-Hotel Krone · Rheinuferstraße 8 · 56154 Boppard am Rhein',
        ];

        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="<?php echo esc_attr( $lang ); ?>">
        <head><meta charset="UTF-8"><title><?php echo esc_html( $t['title'] ); ?></title></head>
        <body style="margin:0;padding:0;background:#f4f4f4;font-family:Georgia,serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;">
        <tr><td align="center" style="padding:40px 20px;">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);">

            <!-- Header -->
            <tr><td style="background:<?php echo $colors['navy']; ?>;padding:40px 30px;text-align:center;">
                <img src="<?php echo esc_url( get_site_url() ); ?>/wp-content/themes/hotel-krone/assets/images/logo-white.png" alt="Rhein-Hotel Krone" style="height:50px;" onerror="this.style.display='none'">
                <h1 style="color:<?php echo $colors['gold']; ?>;margin:10px 0 0;font-size:22px;font-weight:normal;letter-spacing:2px;text-transform:uppercase;">RHEIN-HOTEL KRONE</h1>
            </td></tr>

            <!-- Gold bar -->
            <tr><td style="background:<?php echo $colors['gold']; ?>;height:4px;"></td></tr>

            <!-- Greeting -->
            <tr><td style="padding:40px 40px 20px;text-align:center;">
                <h2 style="color:<?php echo $colors['navy']; ?>;font-size:28px;margin:0 0 10px;"><?php echo esc_html( $t['greeting'] ); ?></h2>
                <p style="color:#6b7280;font-size:16px;margin:0;"><?php echo esc_html( $t['sub'] ); ?></p>
            </td></tr>

            <!-- Reference badge -->
            <tr><td style="padding:0 40px 30px;text-align:center;">
                <div style="display:inline-block;background:<?php echo $colors['gold_light']; ?>;border:2px solid <?php echo $colors['gold']; ?>;border-radius:8px;padding:12px 30px;">
                    <span style="font-size:12px;color:<?php echo $colors['navy']; ?>;text-transform:uppercase;letter-spacing:1px;"><?php echo esc_html( $t['ref'] ); ?></span><br>
                    <strong style="font-size:22px;color:<?php echo $colors['navy']; ?>;"><?php echo esc_html( $reservation->reference ); ?></strong>
                </div>
            </td></tr>

            <!-- Details table -->
            <tr><td style="padding:0 40px 30px;">
                <h3 style="color:<?php echo $colors['navy']; ?>;font-size:16px;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid <?php echo $colors['gold']; ?>;padding-bottom:10px;"><?php echo esc_html( $t['details'] ); ?></h3>
                <table width="100%" cellpadding="8" cellspacing="0">
                    <tr style="border-bottom:1px solid #e8e2d5;">
                        <td style="color:#6b7280;width:40%;"><?php echo esc_html( $t['room'] ); ?></td>
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;"><?php echo esc_html( $room_title ); ?></td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e2d5;">
                        <td style="color:#6b7280;"><?php echo esc_html( $t['checkin'] ); ?></td>
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;">
                            <?php echo esc_html( date_i18n( 'd.m.Y', strtotime( $reservation->check_in ) ) ); ?>
                            <small style="color:#6b7280;font-weight:normal;"> (<?php echo esc_html( $t['checkin_t'] ); ?>: <?php echo esc_html( $checkin_time ); ?>)</small>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e2d5;">
                        <td style="color:#6b7280;"><?php echo esc_html( $t['checkout'] ); ?></td>
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;">
                            <?php echo esc_html( date_i18n( 'd.m.Y', strtotime( $reservation->check_out ) ) ); ?>
                            <small style="color:#6b7280;font-weight:normal;"> (<?php echo esc_html( $t['checkout_t'] ); ?>: <?php echo esc_html( $checkout_time ); ?>)</small>
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e2d5;">
                        <td style="color:#6b7280;"><?php echo esc_html( $t['nights'] ); ?></td>
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;"><?php echo esc_html( $nights ); ?></td>
                    </tr>
                    <tr style="border-bottom:1px solid #e8e2d5;">
                        <td style="color:#6b7280;"><?php echo esc_html( $t['guests'] ); ?></td>
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;"><?php echo esc_html( $reservation->adults ); ?> + <?php echo esc_html( $reservation->children ); ?></td>
                    </tr>
                    <tr style="background:<?php echo $colors['gold_light']; ?>;">
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;font-size:16px;"><?php echo esc_html( $t['total'] ); ?></td>
                        <td style="color:<?php echo $colors['navy']; ?>;font-weight:bold;font-size:20px;">€<?php echo number_format( $reservation->total_price, 2, '.', ',' ); ?></td>
                    </tr>
                </table>
            </td></tr>

            <!-- Contact -->
            <tr><td style="padding:20px 40px 40px;background:#fafaf8;border-top:1px solid #e8e2d5;">
                <p style="color:#6b7280;font-size:14px;margin:0;">
                    <strong style="color:<?php echo $colors['navy']; ?>;">Rhein-Hotel Krone</strong><br>
                    Rheinuferstraße 8, 56154 Boppard am Rhein<br>
                    Tel: +49 6742 2313 | info@rhein-hotel-krone.de
                </p>
            </td></tr>

            <!-- Footer -->
            <tr><td style="background:<?php echo $colors['navy']; ?>;padding:20px 40px;text-align:center;">
                <p style="color:rgba(255,255,255,0.6);font-size:12px;margin:0;"><?php echo esc_html( $t['footer'] ); ?></p>
            </td></tr>

        </table>
        </td></tr>
        </table>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Generate admin notification email HTML.
     */
    private static function get_admin_email_html( $reservation ) {
        $room_title = get_the_title( $reservation->room_id ) ?: 'Room';
        $nights     = (int) ( ( strtotime( $reservation->check_out ) - strtotime( $reservation->check_in ) ) / DAY_IN_SECONDS );
        $colors     = self::email_colors();

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <body style="margin:0;padding:20px;background:#f4f4f4;font-family:Arial,sans-serif;">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;margin:0 auto;">
            <tr><td style="background:<?php echo $colors['navy']; ?>;padding:20px 30px;">
                <h1 style="color:<?php echo $colors['gold']; ?>;margin:0;font-size:18px;">New Booking — <?php echo esc_html( $reservation->reference ); ?></h1>
            </td></tr>
            <tr><td style="padding:30px;">
                <table width="100%" cellpadding="6" cellspacing="0">
                    <tr><td style="color:#6b7280;width:35%;">Guest</td><td style="font-weight:bold;"><?php echo esc_html( $reservation->guest_name ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Email</td><td><?php echo esc_html( $reservation->guest_email ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Phone</td><td><?php echo esc_html( $reservation->guest_phone ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Room</td><td style="font-weight:bold;"><?php echo esc_html( $room_title ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Check-in</td><td><?php echo esc_html( $reservation->check_in ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Check-out</td><td><?php echo esc_html( $reservation->check_out ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Nights</td><td><?php echo esc_html( $nights ); ?></td></tr>
                    <tr><td style="color:#6b7280;">Guests</td><td><?php echo esc_html( $reservation->adults ); ?> adults / <?php echo esc_html( $reservation->children ); ?> children</td></tr>
                    <tr style="background:#f5e9c0;"><td style="font-weight:bold;">Total</td><td style="font-weight:bold;font-size:18px;">€<?php echo number_format( $reservation->total_price, 2 ); ?></td></tr>
                    <?php if ( $reservation->notes ) : ?>
                    <tr><td style="color:#6b7280;">Notes</td><td><?php echo esc_html( $reservation->notes ); ?></td></tr>
                    <?php endif; ?>
                </table>
                <p style="margin-top:20px;">
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=hotel-booking-reservations&action=edit&id=' . $reservation->id ) ); ?>" style="background:<?php echo $colors['navy']; ?>;color:#fff;padding:10px 20px;text-decoration:none;border-radius:4px;">View in Dashboard</a>
                </p>
            </td></tr>
        </table>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    private static function email_colors() {
        return [
            'navy'       => '#1A2535',
            'gold'       => '#C9A84C',
            'gold_light' => '#F5E9C0',
        ];
    }
}
