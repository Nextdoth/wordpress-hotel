<?php
defined( 'ABSPATH' ) || exit;

define( 'HK_VERSION', '1.0.0' );
define( 'HK_DIR',     get_template_directory() );
define( 'HK_URI',     get_template_directory_uri() );

// Load includes
require_once HK_DIR . '/inc/enqueue.php';
require_once HK_DIR . '/inc/post-types.php';
require_once HK_DIR . '/inc/customizer.php';
require_once HK_DIR . '/inc/template-functions.php';
require_once HK_DIR . '/inc/seo.php';

/**
 * Theme setup.
 */
function hk_setup() {
    load_theme_textdomain( 'hotel-krone', HK_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 280,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );

    // Image sizes
    add_image_size( 'hk-hero',       1920, 900, true );
    add_image_size( 'hk-room-card',  600,  450, true );
    add_image_size( 'hk-room-large', 1200, 800, true );
    add_image_size( 'hk-gallery',    800,  600, true );
    add_image_size( 'hk-thumb',      400,  300, true );

    // Navigation menus
    register_nav_menus( [
        'primary'   => __( 'Primary Navigation', 'hotel-krone' ),
        'footer'    => __( 'Footer Navigation',  'hotel-krone' ),
        'languages' => __( 'Language Switcher',  'hotel-krone' ),
    ] );
}
add_action( 'after_setup_theme', 'hk_setup' );

/**
 * Widget areas.
 */
function hk_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Blog Sidebar', 'hotel-krone' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Appears on blog posts and pages.', 'hotel-krone' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
    register_sidebar( [
        'name'          => __( 'Room Sidebar', 'hotel-krone' ),
        'id'            => 'sidebar-rooms',
        'description'   => __( 'Appears on room pages.', 'hotel-krone' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
    register_sidebar( [
        'name'          => __( 'Footer Widgets', 'hotel-krone' ),
        'id'            => 'footer-widgets',
        'description'   => __( 'Appears in the footer.', 'hotel-krone' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'hk_widgets_init' );

/**
 * Limit excerpt length.
 */
add_filter( 'excerpt_length', function() { return 20; } );
add_filter( 'excerpt_more',   function() { return '…'; } );
