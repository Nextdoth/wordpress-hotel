<?php
/**
 * Template Name: Photo Gallery
 * Template Post Type: page
 */
get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Visual Journey', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Photo Gallery', 'hotel-krone' ); ?></h1>
            <p><?php esc_html_e( 'Discover the beauty of Rhein-Hotel Krone and the breathtaking Rhine Valley', 'hotel-krone' ); ?></p>
        </div>
    </div>
</div>

<section class="hk-section" style="background:var(--cream);">
    <div class="container">
        <?php hk_breadcrumbs(); ?>

        <?php
        $gallery_images = hk_get_gallery_images( 24 );
        $has_images = ! empty( $gallery_images );
        ?>

        <?php if ( $has_images ) : ?>

        <!-- Category Filter Tabs -->
        <div class="hk-gallery-filter hk-animate" style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin-bottom:40px;">
            <button class="hk-filter-btn is-active" data-filter="all"><?php esc_html_e( 'All', 'hotel-krone' ); ?></button>
            <button class="hk-filter-btn" data-filter="rooms"><?php esc_html_e( 'Rooms', 'hotel-krone' ); ?></button>
            <button class="hk-filter-btn" data-filter="restaurant"><?php esc_html_e( 'Restaurant', 'hotel-krone' ); ?></button>
            <button class="hk-filter-btn" data-filter="exterior"><?php esc_html_e( 'Exterior', 'hotel-krone' ); ?></button>
            <button class="hk-filter-btn" data-filter="rhine"><?php esc_html_e( 'Rhine Valley', 'hotel-krone' ); ?></button>
        </div>

        <div class="hk-gallery-grid hk-animate">
            <?php foreach ( $gallery_images as $i => $image ) :
                $img_url = get_the_post_thumbnail_url( $image->ID, 'full' );
                $thumb   = get_the_post_thumbnail_url( $image->ID, 'hk-room-card' );
                $caption = esc_attr( $image->post_title ?: __( 'Hotel image', 'hotel-krone' ) );
            ?>
            <div class="hk-gallery-item" data-lightbox="gallery" data-src="<?php echo esc_attr( $img_url ); ?>" data-caption="<?php echo $caption; ?>">
                <img src="<?php echo esc_url( $thumb ?: $img_url ); ?>"
                     alt="<?php echo $caption; ?>"
                     loading="lazy">
                <div class="hk-gallery-overlay"></div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else : ?>

        <!-- Placeholder grid when no gallery posts exist -->
        <div style="text-align:center;margin-bottom:40px;" class="hk-animate">
            <p style="color:var(--text-muted);font-size:1.05rem;"><?php esc_html_e( 'Gallery images will appear here once uploaded. Add images via Gallery Images in the WordPress dashboard.', 'hotel-krone' ); ?></p>
        </div>

        <div class="hk-gallery-grid hk-animate">
            <?php for ( $i = 1; $i <= 8; $i++ ) : ?>
            <div class="hk-gallery-item hk-gallery-placeholder">
                <div style="width:100%;height:100%;min-height:220px;background:linear-gradient(135deg,var(--cream-dark),var(--border));display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                    <?php echo hk_icon( 'view' ); ?>
                </div>
                <div class="hk-gallery-overlay"></div>
            </div>
            <?php endfor; ?>
        </div>

        <?php endif; ?>

        <!-- Lightbox container (JS-powered) -->
        <div class="hk-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Image lightbox', 'hotel-krone' ); ?>">
            <button class="hk-lightbox-close" aria-label="<?php esc_attr_e( 'Close', 'hotel-krone' ); ?>">&#x2715;</button>
            <button class="hk-lightbox-prev" aria-label="<?php esc_attr_e( 'Previous', 'hotel-krone' ); ?>">&#8249;</button>
            <img src="" alt="">
            <button class="hk-lightbox-next" aria-label="<?php esc_attr_e( 'Next', 'hotel-krone' ); ?>">&#8250;</button>
        </div>

    </div>
</section>

<!-- Gallery CTA -->
<section class="hk-section-sm" style="background:var(--white);text-align:center;border-top:1px solid var(--border);">
    <div class="container hk-animate">
        <span class="hk-section-tag"><?php esc_html_e( 'Experience It Yourself', 'hotel-krone' ); ?></span>
        <h3 style="margin-bottom:16px;"><?php esc_html_e( 'Ready to Make Your Own Memories?', 'hotel-krone' ); ?></h3>
        <p style="color:var(--text-muted);max-width:500px;margin:0 auto 24px;"><?php esc_html_e( 'Book your stay at Rhein-Hotel Krone and experience the beauty of the Rhine Valley firsthand.', 'hotel-krone' ); ?></p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url( '/rooms/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Book a Room', 'hotel-krone' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline-dark"><?php esc_html_e( 'Contact Us', 'hotel-krone' ); ?></a>
        </div>
    </div>
</section>

<style>
.hk-filter-btn {
    background: var(--white);
    border: 2px solid var(--border);
    color: var(--text-muted);
    padding: 8px 20px;
    border-radius: 30px;
    font-family: var(--font-sans);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-fast);
}
.hk-filter-btn:hover,
.hk-filter-btn.is-active {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--white);
}
.hk-gallery-placeholder .hk-icon svg { width: 32px; height: 32px; }
</style>

<?php get_footer(); ?>
