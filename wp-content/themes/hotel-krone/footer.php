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

                <!-- Social Links -->
                <div class="hk-social-links">
                    <?php if ( $fb ) : ?>
                    <a href="<?php echo esc_url( $fb ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="Facebook">f</a>
                    <?php endif; ?>
                    <?php if ( $ig ) : ?>
                    <a href="<?php echo esc_url( $ig ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="Instagram">&#9679;</a>
                    <?php endif; ?>
                    <?php if ( $ta ) : ?>
                    <a href="<?php echo esc_url( $ta ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="TripAdvisor">★</a>
                    <?php endif; ?>
                    <?php if ( $bk ) : ?>
                    <a href="<?php echo esc_url( $bk ); ?>" class="hk-social-link" target="_blank" rel="noopener" aria-label="Booking.com">B</a>
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
