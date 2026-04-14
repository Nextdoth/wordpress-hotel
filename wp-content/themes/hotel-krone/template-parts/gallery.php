<?php
defined( 'ABSPATH' ) || exit;

$gallery_items = hk_get_gallery_images( 8 );
?>

<section class="hk-gallery hk-section" id="gallery">
    <div class="container">

        <div class="hk-section-header hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Gallery', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'Experience the Krone', 'hotel-krone' ); ?></h2>
            <div class="hk-divider"></div>
            <p><?php esc_html_e( 'A glimpse into the beauty of our historic hotel, rooms, restaurant, and the stunning Rhine Valley surroundings.', 'hotel-krone' ); ?></p>
        </div>

        <?php if ( ! empty( $gallery_items ) ) : ?>
        <div class="hk-gallery-grid">
            <?php foreach ( $gallery_items as $i => $item ) :
                $img_large = get_the_post_thumbnail_url( $item->ID, 'hk-gallery' );
                $img_thumb = get_the_post_thumbnail_url( $item->ID, 'hk-thumb' );
                if ( ! $img_large ) continue;
            ?>
            <div class="hk-gallery-item hk-animate hk-stagger-<?php echo min( $i + 1, 5 ); ?>">
                <img src="<?php echo esc_url( $img_thumb ?: $img_large ); ?>"
                     data-full="<?php echo esc_url( $img_large ); ?>"
                     alt="<?php echo esc_attr( $item->post_title ?: __( 'Hotel Gallery', 'hotel-krone' ) ); ?>"
                     loading="lazy">
                <div class="hk-gallery-overlay"></div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else : ?>
        <!-- Placeholder gallery when no gallery posts exist -->
        <div class="hk-gallery-grid">
            <?php
            $placeholders = [
                'Lobby',
                'Rhine View Room',
                'Classic Double Room',
                'La Corona Restaurant',
                'Hotel Terrace',
                'Boppard Rhine View',
                'Historic Facade',
                'Breakfast Room',
            ];
            foreach ( $placeholders as $i => $label ) : ?>
            <div class="hk-gallery-item hk-animate hk-stagger-<?php echo min( $i + 1, 5 ); ?>"
                 style="background:var(--cream-dark);display:flex;align-items:center;justify-content:center;min-height:200px;border-radius:4px;">
                <div style="text-align:center;color:var(--text-muted);padding:20px;">
                    <div style="font-size:32px;margin-bottom:8px;">🖼</div>
                    <small><?php echo esc_html( $label ); ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="text-align:center;margin-top:40px;" class="hk-animate">
            <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="btn btn-outline-dark">
                <?php esc_html_e( 'View Full Gallery', 'hotel-krone' ); ?>
            </a>
        </div>

    </div>
</section>
