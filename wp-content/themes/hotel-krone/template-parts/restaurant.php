<?php
defined( 'ABSPATH' ) || exit;

$hours = hk_get_option( 'hk_restaurant_hours', 'Mon–Sun: 12:00–14:30, 18:00–22:00' );
?>

<section class="hk-restaurant hk-section" id="restaurant">
    <div class="container">
        <div class="hk-restaurant-inner">

            <!-- Image -->
            <div class="hk-restaurant-image hk-animate-left">
                <img src="<?php echo esc_url( HK_URI . '/assets/images/restaurant.jpg' ); ?>"
                     alt="<?php esc_attr_e( 'La Corona Restaurant at Rhein-Hotel Krone', 'hotel-krone' ); ?>"
                     width="600" height="700"
                     loading="lazy"
                     onerror="this.parentElement.style.background='var(--cream-dark)'">
            </div>

            <!-- Text -->
            <div class="hk-restaurant-text hk-animate-right">
                <span class="hk-section-tag"><?php esc_html_e( 'Fine Dining', 'hotel-krone' ); ?></span>
                <h2><?php esc_html_e( 'Restaurant', 'hotel-krone' ); ?></h2>
                <p class="hk-restaurant-name">La Corona</p>

                <p><?php esc_html_e( 'Savour the authentic flavours of Italy at our in-house restaurant La Corona, where traditional recipes meet the finest seasonal ingredients. Dine with breathtaking Rhine River views as your backdrop.', 'hotel-krone' ); ?></p>

                <div class="hk-restaurant-highlights">
                    <div class="hk-restaurant-highlight"><?php esc_html_e( 'Authentic Italian cuisine with Rhine Valley wines', 'hotel-krone' ); ?></div>
                    <div class="hk-restaurant-highlight"><?php esc_html_e( 'Fresh pasta, wood-fired pizza, seasonal specials', 'hotel-krone' ); ?></div>
                    <div class="hk-restaurant-highlight"><?php esc_html_e( 'Panoramic river terrace seating (summer)', 'hotel-krone' ); ?></div>
                    <div class="hk-restaurant-highlight"><?php esc_html_e( 'Private dining for groups & special occasions', 'hotel-krone' ); ?></div>
                    <div class="hk-restaurant-highlight"><?php esc_html_e( 'Hotel guests enjoy discounts on dining', 'hotel-krone' ); ?></div>
                </div>

                <div class="hk-restaurant-hours">
                    <?php echo hk_icon( 'clock' ); ?> <?php echo esc_html( $hours ); ?>
                </div>

                <div style="margin-top:24px;display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="<?php echo esc_url( home_url( '/restaurant/' ) ); ?>" class="btn btn-outline-dark">
                        <?php esc_html_e( 'View Menu', 'hotel-krone' ); ?>
                    </a>
                    <a href="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>?subject=Table+Reservation+La+Corona" class="btn btn-primary">
                        <?php esc_html_e( 'Reserve a Table', 'hotel-krone' ); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
