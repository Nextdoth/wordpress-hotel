<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<?php
$post_id  = get_the_ID();
$price    = hk_get_room_meta( $post_id, 'price' );
$adults   = hk_get_room_meta( $post_id, 'adults', 2 );
$children = hk_get_room_meta( $post_id, 'children', 0 );
$size     = hk_get_room_meta( $post_id, 'size' );
$bed      = hk_get_room_meta( $post_id, 'bed' );
$view     = hk_get_room_meta( $post_id, 'view' );
$floor    = hk_get_room_meta( $post_id, 'floor' );
$amenities= hk_get_room_amenities( $post_id );

// Get gallery images from post meta or attached images
$gallery_ids = get_post_meta( $post_id, '_hb_gallery_ids', true );
$gallery_images = [];
if ( $gallery_ids ) {
    foreach ( explode( ',', $gallery_ids ) as $id ) {
        $src = wp_get_attachment_image_src( absint( $id ), 'hk-room-large' );
        if ( $src ) $gallery_images[] = $src[0];
    }
}
?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Our Rooms', 'hotel-krone' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <?php if ( $price ) : ?>
            <p><?php printf( esc_html__( 'From %s per night', 'hotel-krone' ), '<strong style="color:var(--gold-light);">' . hk_price( $price ) . '</strong>' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<section class="hk-section hk-room-single">
    <div class="container">
        <?php hk_breadcrumbs(); ?>

        <!-- Room Gallery -->
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="hk-room-gallery">
            <div class="hk-room-gallery-main hk-gallery-item">
                <?php the_post_thumbnail( 'hk-room-large' ); ?>
                <div class="hk-gallery-overlay"></div>
            </div>
            <?php if ( $gallery_images ) : ?>
                <?php foreach ( array_slice( $gallery_images, 0, 2 ) as $img_url ) : ?>
                <div class="hk-room-gallery-thumb hk-gallery-item">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                    <div class="hk-gallery-overlay"></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Room Layout: Details + Booking Sidebar -->
        <div class="hk-room-layout">

            <!-- Main Content -->
            <div class="hk-room-main hk-animate">
                <h1><?php the_title(); ?></h1>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Room Specs -->
                <div class="hk-room-specs-list" style="margin-top:32px;">
                    <?php if ( $size ) : ?>
                    <div class="hk-room-spec-item">
                        <div class="spec-icon"><?php echo hk_icon( 'size' ); ?></div>
                        <div class="spec-label"><?php esc_html_e( 'Room Size', 'hotel-krone' ); ?></div>
                        <div class="spec-value"><?php echo esc_html( $size ); ?>m²</div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $adults ) : ?>
                    <div class="hk-room-spec-item">
                        <div class="spec-icon"><?php echo hk_icon( 'guests' ); ?></div>
                        <div class="spec-label"><?php esc_html_e( 'Capacity', 'hotel-krone' ); ?></div>
                        <div class="spec-value"><?php echo esc_html( $adults ); ?> + <?php echo esc_html( $children ); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $bed ) : ?>
                    <div class="hk-room-spec-item">
                        <div class="spec-icon"><?php echo hk_icon( 'bed' ); ?></div>
                        <div class="spec-label"><?php esc_html_e( 'Bed Type', 'hotel-krone' ); ?></div>
                        <div class="spec-value"><?php echo esc_html( ucfirst( $bed ) ); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $view ) : ?>
                    <div class="hk-room-spec-item">
                        <div class="spec-icon"><?php echo hk_icon( 'view' ); ?></div>
                        <div class="spec-label"><?php esc_html_e( 'View', 'hotel-krone' ); ?></div>
                        <div class="spec-value"><?php echo esc_html( $view ); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $floor ) : ?>
                    <div class="hk-room-spec-item">
                        <div class="spec-icon"><?php echo hk_icon( 'calendar' ); ?></div>
                        <div class="spec-label"><?php esc_html_e( 'Floor', 'hotel-krone' ); ?></div>
                        <div class="spec-value"><?php echo esc_html( $floor ); ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Amenities -->
                <?php if ( $amenities ) : ?>
                <h3><?php esc_html_e( 'Room Amenities', 'hotel-krone' ); ?></h3>
                <div class="hk-room-amenities-list">
                    <?php foreach ( $amenities as $amenity ) : ?>
                    <span class="hk-room-amenity">✓ <?php echo esc_html( $amenity ); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Booking Sidebar -->
            <aside class="hk-booking-sidebar">
                <div class="hk-booking-sidebar-header">
                    <div class="from"><?php esc_html_e( 'Per night from', 'hotel-krone' ); ?></div>
                    <div class="price"><?php echo $price ? hk_price( $price ) : '—'; ?></div>
                    <div class="per"><?php esc_html_e( 'incl. breakfast', 'hotel-krone' ); ?></div>
                </div>
                <div class="hk-booking-sidebar-body">
                    <?php if ( function_exists( 'hotel_booking' ) ) : ?>
                        <?php echo do_shortcode( '[hotel_booking room_id="' . get_the_ID() . '"]' ); ?>
                    <?php else : ?>
                        <a href="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px;">
                            <?php esc_html_e( 'Request Reservation', 'hotel-krone' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
