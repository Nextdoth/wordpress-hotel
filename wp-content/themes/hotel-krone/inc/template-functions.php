<?php
defined( 'ABSPATH' ) || exit;

/**
 * Return inline SVG icon HTML.
 *
 * @param string $name  Icon name.
 * @param string $class Optional extra CSS class.
 * @return string
 */
function hk_icon( $name, $class = '' ) {
    $cls = 'hk-icon' . ( $class ? ' ' . $class : '' );
    $icons = [
        'location'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>',
        'phone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.7 10.9a19.79 19.79 0 01-3.07-8.67A2 2 0 012.61 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.57a16 16 0 006.5 6.5l.96-.96a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15.92z"/></svg>',
        'email'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>',
        'clock'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
        'guests'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
        'size'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>',
        'bed'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16M2 8h20v12M2 8c0-1.1.9-2 2-2h4a2 2 0 012 2M12 8c0-1.1.9-2 2-2h4a2 2 0 012 2M6 14h12"/></svg>',
        'view'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 6s4-5 11-5 11 5 11 5-4 5-11 5-11-5-11-5z"/><circle cx="12" cy="6" r="3"/></svg>',
        'wifi'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0114.08 0"/><path d="M1.42 9a16 16 0 0121.16 0"/><path d="M8.53 16.11a6 6 0 016.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/></svg>',
        'restaurant' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
        'parking'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 17V7h4a3 3 0 010 6H9"/></svg>',
        'wine'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M8 22h8M7 10h10M12 15v7M17 5H7l-2-3h12l-2 3z"/><path d="M7 10a5 5 0 0010 0V5H7v5z"/></svg>',
        'river'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 7c1.5 0 1.5 2 3 2s1.5-2 3-2 1.5 2 3 2 1.5-2 3-2 1.5 2 3 2"/><path d="M2 12c1.5 0 1.5 2 3 2s1.5-2 3-2 1.5 2 3 2 1.5-2 3-2 1.5 2 3 2"/><path d="M2 17c1.5 0 1.5 2 3 2s1.5-2 3-2 1.5 2 3 2 1.5-2 3-2 1.5 2 3 2"/></svg>',
        'conference' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>',
        'cruise'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M5 20L3 10h18l-2 10M12 3v7M8 10V7l4-4 4 4v3"/></svg>',
        'laundry'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.57a1 1 0 00.99.84H5v10a2 2 0 002 2h10a2 2 0 002-2V10h1.15a1 1 0 00.99-.84l.58-3.57a2 2 0 00-1.34-2.23z"/></svg>',
        'roomservice'=> '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18h18M3 18a9 9 0 0118 0M12 3v3M12 6a7 7 0 017 7H5a7 7 0 017-7z"/></svg>',
        'garden'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22V12M12 12C12 7 7 4 3 6c3 3 5 6 9 6zM12 12c0-5 5-8 9-6-3 3-5 6-9 6z"/></svg>',
        'star'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
        'check'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        'arrow-right'=> '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
        'map'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>',
        'rhine'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 100 20 10 10 0 000-20z"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
        'calendar'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'spa'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C6 22 2 17 2 12c0-1 .2-2 .6-3C5 12 9 14 12 14s7-2 9.4-5c.4 1 .6 2 .6 3 0 5-4 8-10 8z"/><path d="M12 14V2"/></svg>',
    ];
    $svg = $icons[ $name ] ?? $icons['check'];
    return '<span class="' . esc_attr( $cls ) . '" aria-hidden="true">' . $svg . '</span>';
}

/**
 * Render star rating HTML.
 */
function hk_star_rating( $rating = 5 ) {
    $output = '<span class="hk-stars" aria-label="' . esc_attr( sprintf( __( '%d out of 5 stars', 'hotel-krone' ), $rating ) ) . '">';
    for ( $i = 1; $i <= 5; $i++ ) {
        $output .= '<span class="hk-star ' . ( $i <= $rating ? 'hk-star-filled' : 'hk-star-empty' ) . '">★</span>';
    }
    $output .= '</span>';
    return $output;
}

/**
 * Get room meta with defaults.
 */
function hk_get_room_meta( $post_id, $key, $default = '' ) {
    $meta_map = [
        'price'    => '_hb_price_per_night',
        'adults'   => '_hb_capacity_adults',
        'children' => '_hb_capacity_children',
        'size'     => '_hb_room_size',
        'bed'      => '_hb_bed_type',
        'view'     => '_hb_view',
        'floor'    => '_hb_floor',
        'amenities'=> '_hb_amenities',
    ];
    $meta_key = $meta_map[ $key ] ?? $key;
    return get_post_meta( $post_id, $meta_key, true ) ?: $default;
}

/**
 * Get amenities as array.
 */
function hk_get_room_amenities( $post_id ) {
    $raw = hk_get_room_meta( $post_id, 'amenities' );
    if ( ! $raw ) return [];
    return array_map( 'trim', explode( ',', $raw ) );
}

/**
 * Format price.
 */
function hk_price( $amount ) {
    return '€' . number_format( (float) $amount, 0, '.', '.' );
}

/**
 * Get booking form URL.
 */
function hk_booking_url( $room_id = 0 ) {
    $page = get_page_by_path( 'book' ) ?: get_page_by_path( 'booking' );
    $url  = $page ? get_permalink( $page ) : home_url( '/#booking' );
    if ( $room_id ) $url = add_query_arg( 'room', $room_id, $url );
    return $url;
}

/**
 * Language switcher HTML (Polylang or fallback).
 */
function hk_language_switcher() {
    if ( function_exists( 'pll_the_languages' ) ) {
        pll_the_languages( [
            'show_flags'    => 1,
            'show_names'    => 1,
            'display_flags' => true,
            'dropdown'      => 0,
            'echo'          => 1,
        ] );
    } else {
        // Minimal fallback
        $current_lang = substr( get_locale(), 0, 2 );
        $en_url = add_query_arg( 'lang', 'en', get_permalink() );
        $de_url = add_query_arg( 'lang', 'de', get_permalink() );
        echo '<span class="hk-lang-fallback">';
        echo '<a href="' . esc_url( $en_url ) . '" class="' . ( $current_lang === 'en' ? 'hk-lang-active' : '' ) . '">EN</a>';
        echo '<a href="' . esc_url( $de_url ) . '" class="' . ( $current_lang === 'de' ? 'hk-lang-active' : '' ) . '">DE</a>';
        echo '</span>';
    }
}

/**
 * Get featured rooms for homepage.
 *
 * @param int $count
 * @return WP_Post[]
 */
function hk_get_featured_rooms( $count = 4 ) {
    // Prefer plugin's hb_room CPT, fallback to hk_room if plugin inactive
    $post_type = post_type_exists( 'hb_room' ) ? 'hb_room' : 'hk_room';

    return get_posts( [
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );
}

/**
 * Get testimonials for homepage.
 */
function hk_get_testimonials( $count = 6 ) {
    return get_posts( [
        'post_type'      => 'hk_testimonial',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

/**
 * Get gallery images for homepage.
 */
function hk_get_gallery_images( $count = 8 ) {
    return get_posts( [
        'post_type'      => 'hk_gallery',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );
}

/**
 * Check if on a template page.
 */
function hk_is_template( $template ) {
    return is_page_template( HK_DIR . '/page-templates/' . $template . '.php' );
}

/**
 * Breadcrumbs.
 */
function hk_breadcrumbs() {
    if ( is_front_page() ) return;

    echo '<nav class="hk-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'hotel-krone' ) . '">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'hotel-krone' ) . '</a>';
    echo '<span aria-hidden="true"> / </span>';

    if ( is_singular( 'hb_room' ) ) {
        echo '<a href="' . esc_url( get_post_type_archive_link( 'hb_room' ) ) . '">' . esc_html__( 'Rooms', 'hotel-krone' ) . '</a>';
        echo '<span aria-hidden="true"> / </span>';
        echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_single() || is_page() ) {
        echo '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_archive() ) {
        echo '<span aria-current="page">' . esc_html( get_the_archive_title() ) . '</span>';
    } elseif ( is_search() ) {
        echo '<span aria-current="page">' . esc_html__( 'Search Results', 'hotel-krone' ) . '</span>';
    }

    echo '</nav>';
}
