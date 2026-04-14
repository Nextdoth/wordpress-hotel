<?php defined( 'ABSPATH' ) || exit; ?>

<section class="hk-about hk-section" id="about">
    <div class="container">
        <div class="hk-about-inner">

            <!-- Image -->
            <div class="hk-about-image hk-animate-left">
                <img src="<?php echo esc_url( HK_URI . '/assets/images/hotel-exterior.jpg' ); ?>"
                     alt="<?php esc_attr_e( 'Rhein-Hotel Krone exterior view', 'hotel-krone' ); ?>"
                     width="600" height="720"
                     onerror="this.parentElement.style.background='var(--cream-dark)'">
                <div class="hk-about-badge">
                    <span class="year"><?php echo esc_html( hk_get_option( 'hk_founded_year', '950' ) ); ?></span>
                    <span class="label">AD</span>
                </div>
            </div>

            <!-- Text -->
            <div class="hk-about-text hk-animate-right">
                <span class="hk-section-tag"><?php esc_html_e( 'Our Story', 'hotel-krone' ); ?></span>
                <h2><?php esc_html_e( 'A Thousand Years of Rhine River Hospitality', 'hotel-krone' ); ?></h2>
                <p class="hk-tagline"><?php esc_html_e( '"Stay with us, feel like home"', 'hotel-krone' ); ?></p>
                <p><?php esc_html_e( 'Nestled on the banks of the legendary Rhine River in Boppard, the Rhein-Hotel Krone has been welcoming guests since approximately 950 AD. Our historic building carries the weight of centuries, yet offers every modern comfort a discerning traveller could desire.', 'hotel-krone' ); ?></p>
                <p><?php esc_html_e( 'Whether you come for the breathtaking Rhine Valley views, the award-winning La Corona restaurant, or simply to experience the unhurried pace of one of Germany\'s most beautiful river towns — we promise an unforgettable stay.', 'hotel-krone' ); ?></p>

                <div class="hk-about-features">
                    <div class="hk-about-feature">
                        <span class="hk-feature-icon"><?php echo hk_icon( 'cruise' ); ?></span>
                        <div class="hk-feature-text">
                            <h5><?php esc_html_e( 'Historic Building', 'hotel-krone' ); ?></h5>
                            <p><?php esc_html_e( 'Since ~950 AD', 'hotel-krone' ); ?></p>
                        </div>
                    </div>
                    <div class="hk-about-feature">
                        <span class="hk-feature-icon"><?php echo hk_icon( 'river' ); ?></span>
                        <div class="hk-feature-text">
                            <h5><?php esc_html_e( 'Rhine River Views', 'hotel-krone' ); ?></h5>
                            <p><?php esc_html_e( 'Stunning panoramas', 'hotel-krone' ); ?></p>
                        </div>
                    </div>
                    <div class="hk-about-feature">
                        <span class="hk-feature-icon"><?php echo hk_icon( 'wine' ); ?></span>
                        <div class="hk-feature-text">
                            <h5><?php esc_html_e( 'La Corona Restaurant', 'hotel-krone' ); ?></h5>
                            <p><?php esc_html_e( 'Italian cuisine', 'hotel-krone' ); ?></p>
                        </div>
                    </div>
                    <div class="hk-about-feature">
                        <span class="hk-feature-icon"><?php echo hk_icon( 'location' ); ?></span>
                        <div class="hk-feature-text">
                            <h5><?php esc_html_e( 'Central Location', 'hotel-krone' ); ?></h5>
                            <p><?php esc_html_e( 'Boppard town centre', 'hotel-krone' ); ?></p>
                        </div>
                    </div>
                </div>

                <div style="margin-top:32px;">
                    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn-outline-dark">
                        <?php esc_html_e( 'Our History', 'hotel-krone' ); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
