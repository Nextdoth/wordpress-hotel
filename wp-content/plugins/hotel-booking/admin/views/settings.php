<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap hb-admin-wrap">
    <div class="hb-admin-header">
        <h1 class="hb-admin-title"><?php esc_html_e( 'Booking Settings', 'hotel-booking' ); ?></h1>
    </div>

    <?php settings_errors( 'hb_settings' ); ?>

    <?php $s = get_option( 'hotel_booking_settings', [] ); ?>

    <div class="hb-card">
        <form method="post" action="">
            <?php wp_nonce_field( 'hb_save_settings', 'hb_settings_nonce' ); ?>
            <div class="hb-settings-grid">

                <div class="hb-settings-section">
                    <h3><?php esc_html_e( 'Email Settings', 'hotel-booking' ); ?></h3>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'From Name', 'hotel-booking' ); ?></label>
                        <input type="text" name="from_name" value="<?php echo esc_attr( $s['from_name'] ?? 'Rhein-Hotel Krone' ); ?>">
                    </div>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'From Email', 'hotel-booking' ); ?></label>
                        <input type="email" name="from_email" value="<?php echo esc_attr( $s['from_email'] ?? 'info@rhein-hotel-krone.de' ); ?>">
                    </div>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'Admin Notification Email', 'hotel-booking' ); ?></label>
                        <input type="email" name="admin_email" value="<?php echo esc_attr( $s['admin_email'] ?? get_option( 'admin_email' ) ); ?>">
                        <small><?php esc_html_e( 'Receives notification for every new booking.', 'hotel-booking' ); ?></small>
                    </div>
                </div>

                <div class="hb-settings-section">
                    <h3><?php esc_html_e( 'Check-in / Check-out', 'hotel-booking' ); ?></h3>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'Check-in Time', 'hotel-booking' ); ?></label>
                        <input type="time" name="checkin_time" value="<?php echo esc_attr( $s['checkin_time'] ?? '15:00' ); ?>">
                    </div>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'Check-out Time', 'hotel-booking' ); ?></label>
                        <input type="time" name="checkout_time" value="<?php echo esc_attr( $s['checkout_time'] ?? '11:00' ); ?>">
                    </div>
                </div>

                <div class="hb-settings-section">
                    <h3><?php esc_html_e( 'Currency', 'hotel-booking' ); ?></h3>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'Currency Code', 'hotel-booking' ); ?></label>
                        <select name="currency">
                            <option value="EUR" <?php selected( $s['currency'] ?? 'EUR', 'EUR' ); ?>>EUR — Euro</option>
                            <option value="USD" <?php selected( $s['currency'] ?? 'EUR', 'USD' ); ?>>USD — US Dollar</option>
                            <option value="GBP" <?php selected( $s['currency'] ?? 'EUR', 'GBP' ); ?>>GBP — British Pound</option>
                            <option value="CHF" <?php selected( $s['currency'] ?? 'EUR', 'CHF' ); ?>>CHF — Swiss Franc</option>
                        </select>
                    </div>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'Currency Symbol', 'hotel-booking' ); ?></label>
                        <input type="text" name="currency_symbol" value="<?php echo esc_attr( $s['currency_symbol'] ?? '€' ); ?>" style="width:60px;">
                    </div>
                </div>

                <div class="hb-settings-section">
                    <h3><?php esc_html_e( 'Deposit', 'hotel-booking' ); ?></h3>
                    <div class="hb-form-field">
                        <label><?php esc_html_e( 'Deposit Percentage (%)', 'hotel-booking' ); ?></label>
                        <input type="number" name="deposit_percent" value="<?php echo esc_attr( $s['deposit_percent'] ?? 0 ); ?>" min="0" max="100">
                        <small><?php esc_html_e( 'Set to 0 to disable deposit display.', 'hotel-booking' ); ?></small>
                    </div>
                </div>
            </div>

            <div class="hb-form-actions">
                <button type="submit" class="hb-btn-primary"><?php esc_html_e( 'Save Settings', 'hotel-booking' ); ?></button>
            </div>
        </form>
    </div>
</div>
