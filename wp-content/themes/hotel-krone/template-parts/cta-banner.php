<?php defined( 'ABSPATH' ) || exit; ?>

<section class="hk-cta" id="booking">
    <div class="container">
        <div class="hk-cta-content hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Reserve Your Stay', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'Ready for an Unforgettable Rhine River Experience?', 'hotel-krone' ); ?></h2>
            <p class="hk-cta-subtitle"><?php esc_html_e( '"A room with a view and a heart full of memories"', 'hotel-krone' ); ?></p>

            <?php if ( function_exists( 'hotel_booking' ) ) : ?>
                <?php echo do_shortcode( '[hotel_booking]' ); ?>
            <?php else : ?>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', hk_get_option( 'hk_phone', '+4967422313' ) ) ); ?>" class="btn btn-primary btn-lg">
                    <?php echo hk_icon( 'phone' ); ?> <?php esc_html_e( 'Call to Book', 'hotel-krone' ); ?>
                </a>
                <a href="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>?subject=Room+Reservation" class="btn btn-outline btn-lg">
                    <?php echo hk_icon( 'email' ); ?> <?php esc_html_e( 'Email Us', 'hotel-krone' ); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
