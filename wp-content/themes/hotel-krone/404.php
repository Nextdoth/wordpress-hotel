<?php get_header(); ?>

<section class="hk-section">
    <div class="container">
        <div class="hk-404">
            <div class="hk-404-number">404</div>
            <span class="hk-section-tag"><?php esc_html_e( 'Page Not Found', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Oops! This page doesn\'t exist.', 'hotel-krone' ); ?></h1>
            <p style="color:var(--text-muted);max-width:480px;margin:16px auto 32px;"><?php esc_html_e( 'The page you\'re looking for may have been moved or deleted. Let\'s take you back to comfort.', 'hotel-krone' ); ?></p>
            <div style="display:flex;gap:16px;justify-content:center;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( '← Back to Home', 'hotel-krone' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/book/' ) ); ?>" class="btn btn-outline-dark"><?php esc_html_e( 'Book a Room', 'hotel-krone' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
