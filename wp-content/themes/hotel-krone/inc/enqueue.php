<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue theme scripts and styles.
 */
function hk_enqueue_assets() {
    // Google Fonts: Cinzel + Cormorant Garamond + Inter
    wp_enqueue_style(
        'hk-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    // Main theme stylesheet
    wp_enqueue_style( 'hotel-krone-style', get_stylesheet_uri(), [ 'hk-google-fonts' ], HK_VERSION );

    // Main CSS
    wp_enqueue_style(
        'hk-main',
        HK_URI . '/assets/css/main.css',
        [ 'hotel-krone-style' ],
        HK_VERSION
    );

    // Main JS
    wp_enqueue_script(
        'hk-main',
        HK_URI . '/assets/js/main.js',
        [],
        HK_VERSION,
        true
    );

    wp_localize_script( 'hk-main', 'hkTheme', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'homeUrl' => home_url( '/' ),
        'lang'    => get_locale(),
    ] );

    // Comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'hk_enqueue_assets' );

/**
 * Preconnect to Google Fonts for performance.
 */
function hk_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'hk_preconnect_fonts', 1 );

/**
 * Enqueue block editor styles.
 */
function hk_block_editor_styles() {
    wp_enqueue_style(
        'hk-editor-styles',
        HK_URI . '/assets/css/editor.css',
        [],
        HK_VERSION
    );
}
add_action( 'enqueue_block_editor_assets', 'hk_block_editor_styles' );
