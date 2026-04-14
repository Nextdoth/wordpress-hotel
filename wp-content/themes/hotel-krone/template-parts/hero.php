<?php
defined( 'ABSPATH' ) || exit;

$hero_title    = hk_get_option( 'hk_hero_title',    __( 'Experience Historic Luxury on the Rhine', 'hotel-krone' ) );
$hero_subtitle = hk_get_option( 'hk_hero_subtitle', __( 'Since 950 AD — Where History Meets Comfort', 'hotel-krone' ) );
$hero_image_id = hk_get_option( 'hk_hero_image' );
$hero_img_url  = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hk-hero' ) : HK_URI . '/assets/images/hero.jpg';
?>

<section class="hk-hero" id="home" aria-label="<?php esc_attr_e( 'Hero Section', 'hotel-krone' ); ?>">

    <!-- Background Image (parallax target) -->
    <div class="hk-hero-bg" aria-hidden="true">
        <img src="<?php echo esc_url( $hero_img_url ); ?>"
             alt="<?php esc_attr_e( 'Rhein-Hotel Krone — Rhine River View', 'hotel-krone' ); ?>"
             width="1920" height="900"
             fetchpriority="high">
    </div>

    <!-- Dark Overlay -->
    <div class="hk-hero-overlay" aria-hidden="true"></div>

    <!-- Hero Content -->
    <div class="container-wide">
        <div class="hk-hero-content">
            <div class="hk-hero-badge">
                <?php esc_html_e( 'Boppard am Rhein · Germany', 'hotel-krone' ); ?>
            </div>
            <h1><?php echo esc_html( $hero_title ); ?></h1>
            <p class="hk-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <div class="hk-hero-actions">
                <a href="<?php echo esc_url( hk_booking_url() ); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e( 'Book Your Stay', 'hotel-krone' ); ?>
                </a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'hb_room' ) ?: home_url( '/rooms/' ) ); ?>" class="btn btn-outline btn-lg">
                    <?php esc_html_e( 'Explore Rooms', 'hotel-krone' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="hk-scroll-indicator" aria-hidden="true">
        <div class="hk-scroll-mouse"></div>
        <span><?php esc_html_e( 'scroll', 'hotel-krone' ); ?></span>
    </div>

</section>
