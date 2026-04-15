</main><!-- #main-content -->

<?php
$phone    = hk_get_option( 'hk_phone',   '+49 6742 2313' );
$email    = hk_get_option( 'hk_email',   'info@rhein-hotel-krone.de' );
$address  = hk_get_option( 'hk_address', 'Rheinuferstraße 8, 56154 Boppard am Rhein' );
$fb       = hk_get_option( 'hk_facebook' );
$ig       = hk_get_option( 'hk_instagram' );
$ta       = hk_get_option( 'hk_tripadvisor' );
$bk       = hk_get_option( 'hk_booking_com' );
?>

<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="hk-footer-grid">

            <!-- Brand Column -->
            <div class="hk-footer-brand">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="hk-footer-logo"><?php the_custom_logo(); ?></div>
                <?php else : ?>
                    <div class="hk-footer-logo">
                        <div class="hk-logo-text">
                            <span class="hk-logo-top">Hotel</span>
                            <span class="hk-logo-brand">Zur Krone</span>
                        </div>
                    </div>
                <?php endif; ?>
                <p class="hk-footer-tagline"><?php esc_html_e( 'Where History Meets Luxury', 'hotel-krone' ); ?></p>
                <p class="hk-footer-desc"><?php esc_html_e( 'Historic luxury hotel on the banks of the Rhine River in Boppard, Germany. Experience over a thousand years of hospitality.', 'hotel-krone' ); ?></p>

              <div class="hk-social-links">
    <?php if ( $fb ) : ?>
    <a href="<?php echo esc_url( $fb ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="Facebook">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
    </a>
    <?php endif; ?>
    <?php if ( $ig ) : ?>
    <a href="<?php echo esc_url( $ig ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="Instagram">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
    </a>
    <?php endif; ?>
    <?php if ( $ta ) : ?>
    <a href="<?php echo esc_url( $ta ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="TripAdvisor">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 14c-2.67 0-5.03-1.31-6.48-3.32A7.95 7.95 0 0 1 12 14c2.12 0 4.04.83 5.48 2.18A7.93 7.93 0 0 1 12 20z"/></svg>
    </a>
    <?php endif; ?>
    <?php if ( $bk ) : ?>
    <a href="<?php echo esc_url( $bk ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="Booking.com">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6 3h5.5C14 3 16 5 16 7.5c0 1.2-.5 2.3-1.3 3C16 11.2 17 12.7 17 14.5 17 17.5 14.8 20 12 20H6V3zm3 3v4h2.5c1 0 1.5-.7 1.5-2S12.5 6 11.5 6H9zm0 7v4h3c1.1 0 2-.9 2-2s-.9-2-2-2H9z"/></svg>
    </a>
    <?php endif; ?>
</div>

            </div>

            <!-- Navigation -->
            <div class="hk-footer-col">
                <h4><?php esc_html_e( 'Navigation', 'hotel-krone' ); ?></h4>
                <nav class="hk-footer-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'hotel-krone' ); ?>">
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'hk-footer-nav',
                        'depth'          => 1,
                        'fallback_cb'    => function() {
                            $links = [
                                home_url( '/' )          => __( 'Home', 'hotel-krone' ),
                                home_url( '/rooms/' )     => __( 'Rooms', 'hotel-krone' ),
                                home_url( '/restaurant/' )=> __( 'Restaurant', 'hotel-krone' ),
                                home_url( '/gallery/' )   => __( 'Gallery', 'hotel-krone' ),
                                home_url( '/contact/' )   => __( 'Contact', 'hotel-krone' ),
                                home_url( '/book/' )      => __( 'Book Now', 'hotel-krone' ),
                            ];
                            foreach ( $links as $url => $label ) {
                                echo '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
                            }
                        },
                    ] );
                    ?>
                </nav>
            </div>

            <!-- Services -->
            <div class="hk-footer-col">
                <h4><?php esc_html_e( 'Services', 'hotel-krone' ); ?></h4>
                <nav class="hk-footer-nav">
                    <a href="#"><?php esc_html_e( 'Room Service', 'hotel-krone' ); ?></a>
                    <a href="#"><?php esc_html_e( 'Conference Rooms', 'hotel-krone' ); ?></a>
                    <a href="#"><?php esc_html_e( 'Restaurant La Corona', 'hotel-krone' ); ?></a>
                    <a href="#"><?php esc_html_e( 'Parking', 'hotel-krone' ); ?></a>
                    <a href="#"><?php esc_html_e( 'Rhine River Tours', 'hotel-krone' ); ?></a>
                </nav>
            </div>

            <!-- Contact -->
            <div class="hk-footer-col">
                <h4><?php esc_html_e( 'Contact', 'hotel-krone' ); ?></h4>
                <div class="hk-footer-contact">
                    <div class="hk-footer-contact-item">
                        <span class="icon"><?php echo hk_icon( 'location' ); ?></span>
                        <span><?php echo esc_html( $address ); ?></span>
                    </div>
                    <div class="hk-footer-contact-item">
                        <span class="icon"><?php echo hk_icon( 'phone' ); ?></span>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                    </div>
                    <div class="hk-footer-contact-item">
                        <span class="icon"><?php echo hk_icon( 'email' ); ?></span>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="hk-footer-bottom">
            <p class="hk-footer-copyright">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
                <?php esc_html_e( 'All rights reserved.', 'hotel-krone' ); ?>
            </p>
            <div class="hk-footer-links">
                <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'hotel-krone' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/imprint/' ) ); ?>"><?php esc_html_e( 'Imprint', 'hotel-krone' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'hotel-krone' ); ?></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
