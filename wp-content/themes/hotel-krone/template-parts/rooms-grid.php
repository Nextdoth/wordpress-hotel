<?php
defined( 'ABSPATH' ) || exit;

$rooms = hk_get_featured_rooms( 4 );
if ( empty( $rooms ) ) return;
?>

<section class="hk-rooms hk-section" id="rooms">
    <div class="container">

        <div class="hk-section-header hk-animate">
            <span class="hk-section-tag"><?php esc_html_e( 'Accommodation', 'hotel-krone' ); ?></span>
            <h2><?php esc_html_e( 'Our Rooms & Suites', 'hotel-krone' ); ?></h2>
            <div class="hk-divider"></div>
            <p><?php esc_html_e( 'Each room is a sanctuary of comfort — thoughtfully designed with period details, modern amenities, and views of the Rhine or the historic town.', 'hotel-krone' ); ?></p>
        </div>

        <div class="hk-rooms-grid">
            <?php foreach ( $rooms as $i => $room ) :
                $price = hk_get_room_meta( $room->ID, 'price' );
                $adults= hk_get_room_meta( $room->ID, 'adults', 2 );
                $size  = hk_get_room_meta( $room->ID, 'size' );
                $bed   = hk_get_room_meta( $room->ID, 'bed' );
            ?>
            <article class="hk-room-card hk-animate hk-stagger-<?php echo min( $i + 1, 5 ); ?>">

                <div class="hk-room-card-img">
                    <?php if ( has_post_thumbnail( $room->ID ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $room ) ); ?>">
                        <?php echo get_the_post_thumbnail( $room, 'hk-room-card', [ 'loading' => 'lazy' ] ); ?>
                    </a>
                    <?php else : ?>
                    <div style="width:100%;aspect-ratio:4/3;background:var(--cream-dark);display:flex;align-items:center;justify-content:center;color:var(--text-muted);"><?php echo hk_icon( 'bed' ); ?></div>
                    <?php endif; ?>
                    <?php if ( $price ) : ?>
                    <span class="hk-room-price-badge"><?php echo esc_html( hk_price( $price ) ); ?></span>
                    <?php endif; ?>
                </div>

                <div class="hk-room-card-body">
                    <h3>
                        <a href="<?php echo esc_url( get_permalink( $room ) ); ?>">
                            <?php echo esc_html( $room->post_title ); ?>
                        </a>
                    </h3>
                    <p><?php echo esc_html( wp_trim_words( $room->post_excerpt ?: $room->post_content, 18, '…' ) ); ?></p>

                    <div class="hk-room-specs">
                        <?php if ( $adults ) : ?><span class="hk-spec"><?php echo hk_icon( 'guests' ); ?> <?php echo esc_html( $adults ); ?> <?php esc_html_e( 'guests', 'hotel-krone' ); ?></span><?php endif; ?>
                        <?php if ( $size )   : ?><span class="hk-spec"><?php echo hk_icon( 'size' ); ?> <?php echo esc_html( $size ); ?>m²</span><?php endif; ?>
                        <?php if ( $bed )    : ?><span class="hk-spec"><?php echo hk_icon( 'bed' ); ?> <?php echo esc_html( ucfirst( $bed ) ); ?></span><?php endif; ?>
                    </div>

                    <div class="hk-room-card-footer">
                        <div>
                            <div class="hk-room-from"><?php esc_html_e( 'from', 'hotel-krone' ); ?></div>
                            <span class="hk-room-price"><?php echo $price ? esc_html( hk_price( $price ) ) : '—'; ?></span>
                            <span class="hk-room-night">/<?php esc_html_e( 'night', 'hotel-krone' ); ?></span>
                        </div>
                        <a href="<?php echo esc_url( get_permalink( $room ) ); ?>" class="btn btn-primary btn-sm">
                            <?php esc_html_e( 'View & Book', 'hotel-krone' ); ?>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:48px;" class="hk-animate">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'hb_room' ) ?: home_url( '/rooms/' ) ); ?>" class="btn btn-outline-dark">
                <?php esc_html_e( 'View All Rooms', 'hotel-krone' ); ?>
            </a>
        </div>

    </div>
</section>
