<?php
/**
 * Template Name: Rooms Listing
 * Template Post Type: page
 */
get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Accommodation', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Rooms & Suites', 'hotel-krone' ); ?></h1>
            <p><?php esc_html_e( 'Find your perfect retreat with stunning Rhine River views', 'hotel-krone' ); ?></p>
        </div>
    </div>
</div>

<!-- Availability Search Bar -->
<div style="background:var(--cream);padding:0 0 32px;">
    <div class="container">
        <div class="hk-booking-bar" style="margin-top:-36px;position:relative;z-index:10;">
            <form class="hk-bb-form" style="display:contents;" id="rooms-avail-form">
                <div class="hk-bb-field">
                    <label><?php esc_html_e( 'Check-in', 'hotel-krone' ); ?></label>
                    <input type="date" name="check_in" class="hk-bb-check-in" min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                </div>
                <div class="hk-bb-sep" aria-hidden="true"></div>
                <div class="hk-bb-field">
                    <label><?php esc_html_e( 'Check-out', 'hotel-krone' ); ?></label>
                    <input type="date" name="check_out" class="hk-bb-check-out">
                </div>
                <div class="hk-bb-sep" aria-hidden="true"></div>
                <div class="hk-bb-field">
                    <label><?php esc_html_e( 'Adults', 'hotel-krone' ); ?></label>
                    <select name="adults">
                        <?php for ( $i = 1; $i <= 6; $i++ ) : ?>
                        <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="hk-bb-submit"><?php esc_html_e( 'Check Availability', 'hotel-krone' ); ?></button>
            </form>
        </div>
    </div>
</div>

<section class="hk-section hk-rooms">
    <div class="container">
        <?php hk_breadcrumbs(); ?>

        <?php
        $rooms = hk_get_featured_rooms( -1 );
        if ( ! empty( $rooms ) ) : ?>
        <div class="hk-rooms-grid">
            <?php foreach ( $rooms as $i => $room ) :
                $price = hk_get_room_meta( $room->ID, 'price' );
                $adults= hk_get_room_meta( $room->ID, 'adults', 2 );
                $size  = hk_get_room_meta( $room->ID, 'size' );
                $bed   = hk_get_room_meta( $room->ID, 'bed' );
                $view  = hk_get_room_meta( $room->ID, 'view' );
                $amenities = hk_get_room_amenities( $room->ID );
            ?>
            <article class="hk-room-card hk-animate hk-stagger-<?php echo min( ( $i % 4 ) + 1, 5 ); ?>">
                <div class="hk-room-card-img">
                    <?php if ( has_post_thumbnail( $room->ID ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $room ) ); ?>">
                        <?php echo get_the_post_thumbnail( $room, 'hk-room-card', [ 'loading' => 'lazy' ] ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $price ) : ?>
                    <span class="hk-room-price-badge"><?php echo esc_html( hk_price( $price ) ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="hk-room-card-body">
                    <h3><a href="<?php echo esc_url( get_permalink( $room ) ); ?>"><?php echo esc_html( $room->post_title ); ?></a></h3>
                    <p><?php echo esc_html( wp_trim_words( $room->post_excerpt ?: $room->post_content, 20, '…' ) ); ?></p>
                    <div class="hk-room-specs">
                        <?php if ( $adults ) : ?><span class="hk-spec"><?php echo hk_icon( 'guests' ); ?> <?php echo esc_html( $adults ); ?></span><?php endif; ?>
                        <?php if ( $size )   : ?><span class="hk-spec"><?php echo hk_icon( 'size' ); ?> <?php echo esc_html( $size ); ?>m²</span><?php endif; ?>
                        <?php if ( $bed )    : ?><span class="hk-spec"><?php echo hk_icon( 'bed' ); ?> <?php echo esc_html( ucfirst( $bed ) ); ?></span><?php endif; ?>
                        <?php if ( $view )   : ?><span class="hk-spec"><?php echo hk_icon( 'view' ); ?> <?php echo esc_html( $view ); ?></span><?php endif; ?>
                    </div>
                    <?php if ( $amenities ) : ?>
                    <div class="hk-room-amenities-list" style="margin-bottom:12px;">
                        <?php foreach ( array_slice( $amenities, 0, 4 ) as $a ) : ?>
                        <span class="hk-room-amenity" style="font-size:11px;padding:2px 8px;"><?php echo esc_html( $a ); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <div class="hk-room-card-footer">
                        <div>
                            <div class="hk-room-from"><?php esc_html_e( 'from', 'hotel-krone' ); ?></div>
                            <span class="hk-room-price"><?php echo $price ? esc_html( hk_price( $price ) ) : '—'; ?></span>
                            <span class="hk-room-night">/<?php esc_html_e( 'night', 'hotel-krone' ); ?></span>
                        </div>
                        <a href="<?php echo esc_url( get_permalink( $room ) ); ?>" class="btn btn-primary btn-sm"><?php esc_html_e( 'View & Book', 'hotel-krone' ); ?></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div style="text-align:center;padding:80px 0;">
            <p style="color:var(--text-muted);font-size:1.1rem;"><?php esc_html_e( 'Rooms will be added shortly. Please contact us for reservations.', 'hotel-krone' ); ?></p>
            <a href="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>" class="btn btn-primary" style="margin-top:20px;"><?php esc_html_e( 'Contact Us', 'hotel-krone' ); ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
