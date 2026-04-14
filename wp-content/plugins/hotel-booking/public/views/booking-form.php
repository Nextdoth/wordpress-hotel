<?php defined( 'ABSPATH' ) || exit; ?>

<div class="hb-booking-widget" id="hb-booking-widget">

    <!-- Step Indicators -->
    <div class="hb-steps">
        <div class="hb-step hb-step-active" data-step="1">
            <div class="hb-step-num">1</div>
            <div class="hb-step-label"><?php esc_html_e( 'Search', 'hotel-booking' ); ?></div>
        </div>
        <div class="hb-step-line"></div>
        <div class="hb-step" data-step="2">
            <div class="hb-step-num">2</div>
            <div class="hb-step-label"><?php esc_html_e( 'Select Room', 'hotel-booking' ); ?></div>
        </div>
        <div class="hb-step-line"></div>
        <div class="hb-step" data-step="3">
            <div class="hb-step-num">3</div>
            <div class="hb-step-label"><?php esc_html_e( 'Your Details', 'hotel-booking' ); ?></div>
        </div>
        <div class="hb-step-line"></div>
        <div class="hb-step" data-step="4">
            <div class="hb-step-num">4</div>
            <div class="hb-step-label"><?php esc_html_e( 'Confirm', 'hotel-booking' ); ?></div>
        </div>
    </div>

    <!-- ── STEP 1: Date Search ── -->
    <div class="hb-panel" id="hb-step-1">
        <h3 class="hb-panel-title"><?php esc_html_e( 'Check Availability', 'hotel-booking' ); ?></h3>
        <form id="hb-search-form" class="hb-search-form">
            <?php wp_nonce_field( 'hb_nonce', 'hb_nonce' ); ?>
            <input type="hidden" name="action" value="hb_check_availability">
            <?php if ( ! empty( $atts['room_id'] ) ) :
                echo '<input type="hidden" name="room_id" value="' . absint( $atts['room_id'] ) . '">';
            endif; ?>

            <div class="hb-search-grid">
                <div class="hb-field">
                    <label for="hb-check-in"><?php esc_html_e( 'Check-in', 'hotel-booking' ); ?> *</label>
                    <input type="date" id="hb-check-in" name="check_in" required min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                </div>
                <div class="hb-field">
                    <label for="hb-check-out"><?php esc_html_e( 'Check-out', 'hotel-booking' ); ?> *</label>
                    <input type="date" id="hb-check-out" name="check_out" required>
                </div>
                <div class="hb-field">
                    <label for="hb-adults"><?php esc_html_e( 'Adults', 'hotel-booking' ); ?></label>
                    <select id="hb-adults" name="adults">
                        <?php for ( $i = 1; $i <= 6; $i++ ) : ?>
                        <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="hb-field">
                    <label for="hb-children"><?php esc_html_e( 'Children', 'hotel-booking' ); ?></label>
                    <select id="hb-children" name="children">
                        <?php for ( $i = 0; $i <= 4; $i++ ) : ?>
                        <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="hb-btn-search" id="hb-search-btn">
                <span class="hb-btn-text"><?php esc_html_e( 'Check Availability', 'hotel-booking' ); ?></span>
                <span class="hb-btn-spinner" style="display:none;">⟳</span>
            </button>
            <div class="hb-form-error" id="hb-search-error"></div>
        </form>
    </div>

    <!-- ── STEP 2: Room Selection ── -->
    <div class="hb-panel" id="hb-step-2" style="display:none;">
        <div class="hb-panel-header">
            <h3 class="hb-panel-title"><?php esc_html_e( 'Available Rooms', 'hotel-booking' ); ?></h3>
            <button class="hb-back-btn" id="hb-back-to-1">← <?php esc_html_e( 'Change Dates', 'hotel-booking' ); ?></button>
        </div>
        <div class="hb-dates-summary" id="hb-dates-summary"></div>
        <div id="hb-rooms-list" class="hb-rooms-list"></div>
        <div class="hb-form-error" id="hb-rooms-error"></div>
    </div>

    <!-- ── STEP 3: Guest Details ── -->
    <div class="hb-panel" id="hb-step-3" style="display:none;">
        <div class="hb-panel-header">
            <h3 class="hb-panel-title"><?php esc_html_e( 'Your Details', 'hotel-booking' ); ?></h3>
            <button class="hb-back-btn" id="hb-back-to-2">← <?php esc_html_e( 'Back', 'hotel-booking' ); ?></button>
        </div>
        <div class="hb-booking-summary-bar" id="hb-booking-bar"></div>

        <form id="hb-guest-form" class="hb-guest-form">
            <div class="hb-guest-grid">
                <div class="hb-field hb-field-full">
                    <label for="hb-guest-name"><?php esc_html_e( 'Full Name', 'hotel-booking' ); ?> *</label>
                    <input type="text" id="hb-guest-name" name="guest_name" required autocomplete="name" placeholder="<?php esc_attr_e( 'Your full name', 'hotel-booking' ); ?>">
                </div>
                <div class="hb-field">
                    <label for="hb-guest-email"><?php esc_html_e( 'Email Address', 'hotel-booking' ); ?> *</label>
                    <input type="email" id="hb-guest-email" name="guest_email" required autocomplete="email" placeholder="your@email.com">
                </div>
                <div class="hb-field">
                    <label for="hb-guest-phone"><?php esc_html_e( 'Phone Number', 'hotel-booking' ); ?></label>
                    <input type="tel" id="hb-guest-phone" name="guest_phone" autocomplete="tel" placeholder="+49 ...">
                </div>
                <div class="hb-field hb-field-full">
                    <label for="hb-notes"><?php esc_html_e( 'Special Requests', 'hotel-booking' ); ?></label>
                    <textarea id="hb-notes" name="notes" rows="3" placeholder="<?php esc_attr_e( 'Any special requests or notes...', 'hotel-booking' ); ?>"></textarea>
                </div>
            </div>
            <div class="hb-form-error" id="hb-guest-error"></div>
            <button type="submit" class="hb-btn-continue" id="hb-guest-btn">
                <?php esc_html_e( 'Continue to Confirmation', 'hotel-booking' ); ?> →
            </button>
        </form>
    </div>

    <!-- ── STEP 4: Confirm & Book ── -->
    <div class="hb-panel" id="hb-step-4" style="display:none;">
        <div class="hb-panel-header">
            <h3 class="hb-panel-title"><?php esc_html_e( 'Confirm Your Booking', 'hotel-booking' ); ?></h3>
            <button class="hb-back-btn" id="hb-back-to-3">← <?php esc_html_e( 'Back', 'hotel-booking' ); ?></button>
        </div>

        <div id="hb-confirm-summary" class="hb-confirm-summary"></div>

        <div class="hb-confirm-terms">
            <label class="hb-checkbox-label">
                <input type="checkbox" id="hb-terms-check" required>
                <span><?php printf(
                    esc_html__( 'I agree to the %sTerms & Conditions%s and %sPrivacy Policy%s.', 'hotel-booking' ),
                    '<a href="#" target="_blank">', '</a>',
                    '<a href="#" target="_blank">', '</a>'
                ); ?></span>
            </label>
        </div>

        <div class="hb-form-error" id="hb-confirm-error"></div>

        <button class="hb-btn-book" id="hb-book-btn">
            <span class="hb-btn-text">✓ <?php esc_html_e( 'Confirm & Book', 'hotel-booking' ); ?></span>
            <span class="hb-btn-spinner" style="display:none;">⟳</span>
        </button>
    </div>

    <!-- ── SUCCESS STATE ── -->
    <div class="hb-panel hb-success-panel" id="hb-success" style="display:none;">
        <div class="hb-success-icon">✓</div>
        <h2><?php esc_html_e( 'Booking Confirmed!', 'hotel-booking' ); ?></h2>
        <p><?php esc_html_e( 'Your reservation is confirmed. A confirmation email has been sent.', 'hotel-booking' ); ?></p>
        <div class="hb-success-ref" id="hb-success-ref"></div>
    </div>

</div>
