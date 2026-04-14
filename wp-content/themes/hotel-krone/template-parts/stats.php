<?php defined( 'ABSPATH' ) || exit; ?>

<section class="hk-stats hk-section-sm" id="stats">
    <div class="container">
        <div class="hk-stats-grid">

            <div class="hk-stat hk-animate hk-stagger-1">
                <div class="hk-stat-number" data-target="<?php echo esc_attr( hk_get_option( 'hk_founded_year', '950' ) ); ?>">
                    <?php echo esc_html( hk_get_option( 'hk_founded_year', '950' ) ); ?>
                </div>
                <div class="hk-stat-label"><?php esc_html_e( 'Founded', 'hotel-krone' ); ?></div>
            </div>

            <div class="hk-stat hk-animate hk-stagger-2">
                <div class="hk-stat-number" data-target="<?php echo esc_attr( hk_get_option( 'hk_total_rooms', '35' ) ); ?>">
                    <?php echo esc_html( hk_get_option( 'hk_total_rooms', '35' ) ); ?>
                </div>
                <div class="hk-stat-label"><?php esc_html_e( 'Rooms & Suites', 'hotel-krone' ); ?></div>
            </div>

            <div class="hk-stat hk-animate hk-stagger-3">
                <div class="hk-stat-number" data-target="<?php echo esc_attr( hk_get_option( 'hk_review_score', '4.8' ) ); ?>">
                    <?php echo esc_html( hk_get_option( 'hk_review_score', '4.8' ) ); ?>
                </div>
                <div class="hk-stat-suffix" aria-hidden="true">★</div>
                <div class="hk-stat-label"><?php esc_html_e( 'Guest Rating', 'hotel-krone' ); ?></div>
            </div>

            <div class="hk-stat hk-animate hk-stagger-4">
                <div class="hk-stat-number" data-target="1000">1000</div>
                <div class="hk-stat-suffix">+</div>
                <div class="hk-stat-label"><?php esc_html_e( 'Years of Hospitality', 'hotel-krone' ); ?></div>
            </div>

        </div>
    </div>
</section>
