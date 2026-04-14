<?php
defined( 'ABSPATH' ) || exit;

/**
 * SEO: Meta tags, Open Graph, Schema.org JSON-LD.
 * Gracefully degrades if Yoast SEO is active.
 */

/**
 * Output meta description and Open Graph tags in <head>.
 */
function hk_meta_tags() {
    // Yoast handles this if active
    if ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'Rank_Math' ) ) {
        return;
    }

    global $post;
    $description = '';
    $image       = '';
    $title       = '';

    if ( is_singular() && $post ) {
        $description = $post->post_excerpt ?: wp_trim_words( $post->post_content, 30, '' );
        $title       = get_the_title() . ' | ' . get_bloginfo( 'name' );
        $image_id    = get_post_thumbnail_id( $post->ID );
        if ( $image_id ) {
            $img   = wp_get_attachment_image_src( $image_id, 'large' );
            $image = $img ? $img[0] : '';
        }
    } elseif ( is_home() || is_front_page() ) {
        $description = get_bloginfo( 'description' ) ?: __( 'Historic luxury hotel on the Rhine River in Boppard, Germany. Enjoy comfort and history since 950 AD.', 'hotel-krone' );
        $title       = get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' );
        $hero_id     = get_theme_mod( 'hk_hero_image' );
        if ( $hero_id ) {
            $img   = wp_get_attachment_image_src( $hero_id, 'large' );
            $image = $img ? $img[0] : '';
        }
    } else {
        $description = get_bloginfo( 'description' );
        $title       = wp_title( '|', false, 'right' ) . get_bloginfo( 'name' );
    }

    $description = esc_attr( wp_strip_all_tags( $description ) );
    $site_url    = home_url( '/' );
    $current_url = ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    echo '<meta name="description" content="' . $description . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( $current_url ) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . $description . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $current_url ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
}
add_action( 'wp_head', 'hk_meta_tags', 5 );

/**
 * Output Schema.org Hotel JSON-LD in footer.
 */
function hk_schema_jsonld() {
    if ( ! is_front_page() && ! is_home() ) {
        return;
    }

    $phone   = hk_get_option( 'hk_phone', '+49 6742 2313' );
    $email   = hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' );
    $address = hk_get_option( 'hk_address', 'Rheinuferstraße 8, 56154 Boppard am Rhein' );
    $score   = hk_get_option( 'hk_review_score', '4.8' );

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Hotel',
        'name'        => 'Rhein-Hotel Krone',
        'url'         => home_url( '/' ),
        'logo'        => HK_URI . '/assets/images/logo.png',
        'description' => __( 'Historic luxury hotel on the Rhine River in Boppard, Germany. Since ~950 AD. Rooms, restaurant La Corona, breathtaking river views.', 'hotel-krone' ),
        'telephone'   => $phone,
        'email'       => $email,
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Rheinuferstraße 8',
            'addressLocality' => 'Boppard',
            'postalCode'      => '56154',
            'addressRegion'   => 'Rhineland-Palatinate',
            'addressCountry'  => 'DE',
        ],
        'geo' => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => '50.2309',
            'longitude' => '7.5878',
        ],
        'starRating' => [
            '@type'       => 'Rating',
            'ratingValue' => '4',
        ],
        'aggregateRating' => [
            '@type'       => 'AggregateRating',
            'ratingValue' => $score,
            'reviewCount' => '200',
            'bestRating'  => '5',
        ],
        'hasMap' => 'https://maps.google.com/?q=Rhein-Hotel+Krone+Boppard',
        'amenityFeature' => [
            [ '@type' => 'LocationFeatureSpecification', 'name' => 'Free WiFi',        'value' => true ],
            [ '@type' => 'LocationFeatureSpecification', 'name' => 'Restaurant',        'value' => true ],
            [ '@type' => 'LocationFeatureSpecification', 'name' => 'Parking',           'value' => true ],
            [ '@type' => 'LocationFeatureSpecification', 'name' => 'Bar',               'value' => true ],
            [ '@type' => 'LocationFeatureSpecification', 'name' => 'Rhine River View',  'value' => true ],
        ],
        'priceRange'     => '€€',
        'currenciesAccepted' => 'EUR',
        'paymentAccepted'    => 'Cash, Credit Card, Debit Card',
        'openingHours'       => 'Mo-Su 00:00-23:59',
    ];

    echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_footer', 'hk_schema_jsonld' );

/**
 * Schema for individual room pages.
 */
function hk_room_schema() {
    if ( ! is_singular( 'hb_room' ) ) return;

    $post_id   = get_the_ID();
    $price     = hk_get_room_meta( $post_id, 'price' );
    $room_name = get_the_title();
    $img_url   = get_the_post_thumbnail_url( $post_id, 'large' );

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'HotelRoom',
        'name'        => $room_name,
        'description' => get_the_excerpt(),
        'url'         => get_permalink(),
        'image'       => $img_url,
        'containedInPlace' => [
            '@type' => 'Hotel',
            'name'  => 'Rhein-Hotel Krone',
            'url'   => home_url( '/' ),
        ],
    ];

    if ( $price ) {
        $schema['offers'] = [
            '@type'         => 'Offer',
            'price'         => $price,
            'priceCurrency' => 'EUR',
            'priceSpecification' => [
                '@type'    => 'UnitPriceSpecification',
                'price'    => $price,
                'priceCurrency' => 'EUR',
                'unitText' => 'night',
            ],
        ];
    }

    echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_footer', 'hk_room_schema' );
