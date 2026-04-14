<?php
defined( 'ABSPATH' ) || exit;

/**
 * Theme Customizer options.
 */
function hk_customizer_register( $wp_customize ) {

    // ─── Hotel Info Panel ─────────────────────────────────────────────────
    $wp_customize->add_panel( 'hk_hotel_info', [
        'title'    => __( 'Hotel Information', 'hotel-krone' ),
        'priority' => 30,
    ] );

    // ─── Contact Section ─────────────────────────────────────────────────
    $wp_customize->add_section( 'hk_contact', [
        'title' => __( 'Contact Details', 'hotel-krone' ),
        'panel' => 'hk_hotel_info',
    ] );

    $contact_fields = [
        'hk_phone'   => [ 'label' => __( 'Phone', 'hotel-krone' ),   'default' => '+49 6742 2313' ],
        'hk_email'   => [ 'label' => __( 'Email', 'hotel-krone' ),   'default' => 'info@rhein-hotel-krone.de' ],
        'hk_address' => [ 'label' => __( 'Address', 'hotel-krone' ), 'default' => 'Rheinuferstraße 8, 56154 Boppard am Rhein' ],
        'hk_map_url' => [ 'label' => __( 'Google Maps URL', 'hotel-krone' ), 'default' => 'https://maps.google.com/?q=Rhein-Hotel+Krone+Boppard' ],
    ];

    foreach ( $contact_fields as $id => $opts ) {
        $wp_customize->add_setting( $id, [ 'default' => $opts['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $id, [ 'label' => $opts['label'], 'section' => 'hk_contact', 'type' => 'text' ] );
    }

    // ─── Social Links ─────────────────────────────────────────────────────
    $wp_customize->add_section( 'hk_social', [
        'title' => __( 'Social Media', 'hotel-krone' ),
        'panel' => 'hk_hotel_info',
    ] );

    $social_fields = [
        'hk_facebook'   => __( 'Facebook URL', 'hotel-krone' ),
        'hk_instagram'  => __( 'Instagram URL', 'hotel-krone' ),
        'hk_tripadvisor'=> __( 'TripAdvisor URL', 'hotel-krone' ),
        'hk_booking_com'=> __( 'Booking.com URL', 'hotel-krone' ),
    ];

    foreach ( $social_fields as $id => $label ) {
        $wp_customize->add_setting( $id, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( $id, [ 'label' => $label, 'section' => 'hk_social', 'type' => 'url' ] );
    }

    // ─── Hero Section ─────────────────────────────────────────────────────
    $wp_customize->add_section( 'hk_hero', [
        'title' => __( 'Hero Section', 'hotel-krone' ),
        'panel' => 'hk_hotel_info',
    ] );

    $wp_customize->add_setting( 'hk_hero_title', [
        'default'           => __( 'Experience Historic Luxury on the Rhine', 'hotel-krone' ),
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'hk_hero_title', [
        'label'   => __( 'Hero Title', 'hotel-krone' ),
        'section' => 'hk_hero',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'hk_hero_subtitle', [
        'default'           => __( 'Since 950 AD — Where History Meets Comfort', 'hotel-krone' ),
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'hk_hero_subtitle', [
        'label'   => __( 'Hero Subtitle', 'hotel-krone' ),
        'section' => 'hk_hero',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'hk_hero_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'hk_hero_image', [
        'label'     => __( 'Hero Background Image', 'hotel-krone' ),
        'section'   => 'hk_hero',
        'mime_type' => 'image',
    ] ) );

    // ─── Restaurant Section ───────────────────────────────────────────────
    $wp_customize->add_section( 'hk_restaurant', [
        'title' => __( 'Restaurant La Corona', 'hotel-krone' ),
        'panel' => 'hk_hotel_info',
    ] );

    $wp_customize->add_setting( 'hk_restaurant_hours', [
        'default'           => 'Mon–Sun: 12:00–14:30, 18:00–22:00',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'hk_restaurant_hours', [
        'label'   => __( 'Opening Hours', 'hotel-krone' ),
        'section' => 'hk_restaurant',
        'type'    => 'text',
    ] );

    // ─── Statistics ───────────────────────────────────────────────────────
    $wp_customize->add_section( 'hk_stats', [
        'title' => __( 'Homepage Stats', 'hotel-krone' ),
        'panel' => 'hk_hotel_info',
    ] );

    $wp_customize->add_setting( 'hk_founded_year', [ 'default' => '950',  'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'hk_founded_year', [ 'label' => __( 'Founded Year', 'hotel-krone' ), 'section' => 'hk_stats', 'type' => 'text' ] );

    $wp_customize->add_setting( 'hk_total_rooms', [ 'default' => '35',   'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( 'hk_total_rooms', [ 'label' => __( 'Total Rooms', 'hotel-krone' ), 'section' => 'hk_stats', 'type' => 'number' ] );

    $wp_customize->add_setting( 'hk_review_score', [ 'default' => '4.8',  'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'hk_review_score', [ 'label' => __( 'Review Score (e.g. 4.8)', 'hotel-krone' ), 'section' => 'hk_stats', 'type' => 'text' ] );

    $wp_customize->add_setting( 'hk_years_experience', [ 'default' => '1000+', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'hk_years_experience', [ 'label' => __( 'Years of Hospitality', 'hotel-krone' ), 'section' => 'hk_stats', 'type' => 'text' ] );
}
add_action( 'customize_register', 'hk_customizer_register' );

/**
 * Helper: get customizer value with fallback.
 */
function hk_get_option( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}
