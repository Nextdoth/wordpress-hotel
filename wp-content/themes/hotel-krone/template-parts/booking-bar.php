<?php defined( 'ABSPATH' ) || exit; ?>

<!-- Inline booking bar below hero -->
<div style="background:var(--cream);padding:0 0 32px;">
    <div class="container">
        <div class="hk-booking-bar" style="margin-top:-36px;position:relative;z-index:10;">
            <form class="hk-bb-form" style="display:contents;" action="<?php echo esc_url( hk_booking_url() ); ?>" method="get">
                <div class="hk-bb-field">
                    <label for="bb-check-in"><?php esc_html_e( 'Check-in', 'hotel-krone' ); ?></label>
                    <input type="date" id="bb-check-in" name="check_in" class="hk-bb-check-in" min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" aria-label="<?php esc_attr_e( 'Check-in date', 'hotel-krone' ); ?>">
                </div>
                <div class="hk-bb-sep" aria-hidden="true"></div>
                <div class="hk-bb-field">
                    <label for="bb-check-out"><?php esc_html_e( 'Check-out', 'hotel-krone' ); ?></label>
                    <input type="date" id="bb-check-out" name="check_out" class="hk-bb-check-out" aria-label="<?php esc_attr_e( 'Check-out date', 'hotel-krone' ); ?>">
                </div>
                <div class="hk-bb-sep" aria-hidden="true"></div>
                <div class="hk-bb-field">
                    <label for="bb-adults"><?php esc_html_e( 'Adults', 'hotel-krone' ); ?></label>
                    <select id="bb-adults" name="adults">
                        <?php for ( $i = 1; $i <= 6; $i++ ) : ?>
                        <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="hk-bb-sep" aria-hidden="true"></div>
                <div class="hk-bb-field">
                    <label for="bb-children"><?php esc_html_e( 'Children', 'hotel-krone' ); ?></label>
                    <select id="bb-children" name="children">
                        <?php for ( $i = 0; $i <= 4; $i++ ) : ?>
                        <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="hk-bb-submit">
                    <?php esc_html_e( 'Check Availability', 'hotel-krone' ); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Floating Booking Bar (appears after hero scrolled out) -->
<div class="hk-booking-bar-float">
    <div class="hk-booking-bar">
        <form class="hk-bb-form" style="display:contents;" action="<?php echo esc_url( hk_booking_url() ); ?>" method="get">
            <div class="hk-bb-field">
                <label><?php esc_html_e( 'Check-in', 'hotel-krone' ); ?></label>
                <input type="date" name="check_in" class="hk-bb-check-in" min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
            </div>
            <div class="hk-bb-sep" aria-hidden="true"></div>
            <div class="hk-bb-field">
                <label><?php esc_html_e( 'Check-out', 'hotel-krone' ); ?></label>
                <input type="date" name="check_out" class="hk-bb-check-out">
            </div>
            <div class="hk-bb-sep" aria-hidden="true"></div>
            <div class="hk-bb-field">
                <label><?php esc_html_e( 'Guests', 'hotel-krone' ); ?></label>
                <select name="adults">
                    <?php for ( $i = 1; $i <= 6; $i++ ) : ?>
                    <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?> <?php esc_html_e( 'adult(s)', 'hotel-krone' ); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="hk-bb-submit"><?php esc_html_e( 'Check Availability', 'hotel-krone' ); ?></button>
        </form>
    </div>
</div>
