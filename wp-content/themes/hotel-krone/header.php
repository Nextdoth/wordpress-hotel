<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A2535">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'hotel-krone' ); ?></a>

<header class="site-header <?php echo is_front_page() ? 'hk-transparent' : 'hk-solid'; ?>" role="banner">
    <div class="container-wide">
        <nav class="hk-nav-inner" role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'hotel-krone' ); ?>">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <div class="hk-logo-text">
                        <span class="hk-logo-top">Hotel</span>
                        <span class="hk-logo-brand">Zur Krone</span>
                    </div>
                <?php endif; ?>
            </a>

            <!-- Primary Navigation -->
            <div class="primary-nav" id="primary-nav" role="navigation">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => function() {
                        echo '<ul id="primary-menu">';
                        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'hotel-krone' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( get_post_type_archive_link( 'hb_room' ) ?: home_url( '/rooms/' ) ) . '">' . esc_html__( 'Rooms', 'hotel-krone' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/restaurant/' ) ) . '">' . esc_html__( 'Restaurant', 'hotel-krone' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'Contact', 'hotel-krone' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/book/' ) ) . '" class="hk-nav-book">Book Now</a></li>';
                        echo '</ul>';
                    },
                ] );
                ?>
            </div>

            <!-- Right Side -->
            <div class="hk-nav-right">
                <!-- Language Switcher -->
                <div class="hk-lang-switcher">
                    <?php hk_language_switcher(); ?>
                </div>

                <!-- Mobile Toggle -->
                <button class="hk-menu-toggle" id="hk-menu-toggle" aria-controls="primary-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle Menu', 'hotel-krone' ); ?>">
                    <span class="hk-hamburger">
                        <span></span><span></span><span></span>
                    </span>
                </button>
            </div>

        </nav>
    </div>
</header>

<main id="main-content" role="main">
